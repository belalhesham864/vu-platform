<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Models\payments;
use App\Models\Plan;
use App\Models\subscriptions as SubscriptionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Subscription;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function create(CreatePaymentRequest $request)
    {
        $plan = Plan::findOrFail($request->plan_id);
        if ($plan->is_custom) {
            return apiResponse(404, 'Contact Sales');
        }

        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return apiResponse(400, 'User company not found');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        if (!$company->stripe_customer_id) {
            $customer = Customer::create([
                'name' => $company->company_name,
                'email' => $user->email
            ]);
            $company->update([
                'stripe_customer_id' => $customer->id
            ]);
        }

        $setupIntent = SetupIntent::create([
            'customer' => $company->stripe_customer_id,
            'payment_method_types' => ['card']
        ]);

        return apiResponse(200, 'Payment created', [
            'client_secret' => $setupIntent->client_secret,
        ]);
    }

    public function subscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method_id' => 'required|string',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return apiResponse(400, 'User company not found');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        if (!$company->stripe_customer_id) {
            $customer = Customer::create([
                'name' => $company->company_name,
                'email' => $user->email
            ]);
            $company->update([
                'stripe_customer_id' => $customer->id
            ]);
        }

        try {
            $paymentMethod = PaymentMethod::retrieve($request->payment_method_id);

            if ($paymentMethod->customer && $paymentMethod->customer !== $company->stripe_customer_id) {
                // PM belongs to a different customer — reject it
                return apiResponse(400, 'This payment method belongs to another customer.');
            }

            if (!$paymentMethod->customer) {
                // PM not attached to any customer — attach it
                $paymentMethod->attach(['customer' => $company->stripe_customer_id]);
            }

            Customer::update($company->stripe_customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $request->payment_method_id,
                ],
            ]);

            $subscription = Subscription::create([
                'customer' => $company->stripe_customer_id,
                'items' => [
                    ['price' => $plan->stripe_price_id]
                ],
                'default_payment_method' => $request->payment_method_id,
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Stripe error during subscription: ' . $e->getMessage());
            return apiResponse(400, $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Subscription error: ' . $e->getMessage());
            return apiResponse(500, 'Failed to create subscription. Please try again.');
        }

        $clientSecret = null;
        $paymentIntentId = null;

        try {
            $invoice = $subscription->latest_invoice;

            // لو invoice عبارة عن string ID → جيبه من Stripe مع expand
            if (is_string($invoice)) {
                $invoice = \Stripe\Invoice::retrieve([
                    'id' => $invoice,
                    'expand' => ['payment_intent'],
                ]);
            }

            // لو الـ invoice موجود بس payment_intent مش متوفر → نجيب الـ invoice بـ expand
            if ($invoice && empty($invoice->payment_intent) && !empty($invoice->id)) {
                $invoice = \Stripe\Invoice::retrieve([
                    'id' => $invoice->id,
                    'expand' => ['payment_intent'],
                ]);
            }

            if ($invoice && !empty($invoice->payment_intent)) {
                $paymentIntent = $invoice->payment_intent;

                if (is_object($paymentIntent)) {
                    $clientSecret   = $paymentIntent->client_secret ?? null;
                    $paymentIntentId = $paymentIntent->id ?? null;
                } elseif (is_string($paymentIntent)) {
                    $paymentIntentId = $paymentIntent;
                    $pi = \Stripe\PaymentIntent::retrieve($paymentIntent);
                    $clientSecret   = $pi->client_secret ?? null;
                }
            }

            // Fallback: لو لسه clientSecret null، نسترجه من أحدث PaymentIntent للعميل
            if (!$clientSecret && !empty($company->stripe_customer_id)) {
                $pis = \Stripe\PaymentIntent::all([
                    'customer' => $company->stripe_customer_id,
                    'limit' => 1,
                ]);

                if (!empty($pis->data)) {
                    $latestPi = $pis->data[0];
                    $clientSecret   = $latestPi->client_secret ?? null;
                    $paymentIntentId = $latestPi->id ?? null;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Could not retrieve client_secret: ' . $e->getMessage());
        }


        $payment = payments::create([
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'stripe_payment_intent_id' => $paymentIntentId,
            'stripe_subscription_id' => $subscription->id,
            'amount' => $plan->price,
            'status' => 'pending'
        ]);

        $requiresAction = ($subscription->status === 'incomplete' || $subscription->status === 'past_due');

        return apiResponse(200, 'Subscription created', [
            'subscription_id' => $subscription->id,
            'status'          => $subscription->status,
            'client_secret'   => $clientSecret,
            'requires_action' => $requiresAction,
        ]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type == 'invoice.payment_succeeded') {
            $this->handleInvoicesSuccess($event->data->object);
        } elseif ($event->type == 'invoice.payment_failed') {
            $this->handleInvoicesFailed($event->data->object);
        } elseif ($event->type == 'customer.subscription.updated') {
            $this->handlesubscraptionUpdated($event->data->object);
        } elseif ($event->type == 'customer.subscription.deleted') {
            $this->handlesubscraptionCancled($event->data->object);
        } else {
            Log::info('Stripe Event unhandled: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    public function handleInvoicesSuccess($invoice)
    {
        $payment = payments::where('stripe_subscription_id', $invoice->subscription)->first();
        if (!$payment) {
            Log::error('No payment found for subscription: ' . $invoice->subscription);
            return;
        }

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            $subscription = SubscriptionModel::firstOrCreate(
                ['payment_id' => $payment->id],
                [
                    'company_id' => $payment->company_id,
                    'plan_id' => $payment->plan_id,
                    'start_at' => now(),
                    'end_at' => now()->addMonth(),
                    'status' => 'active',
                ]
            );

            $subscription->update([
                'end_at' => now()->addMonth(),
                'status' => 'active'
            ]);
        });
    }

    public function handleInvoicesFailed($invoice)
    {
        $payment = payments::where('stripe_subscription_id', $invoice->subscription)->first();
        if (!$payment) {
            Log::error('No payment found for subscription: ' . $invoice->subscription);
            return;
        }

        $payment->update([
            'status' => 'failed',
            'paid_at' => now(),
        ]);
    }

    public function handlesubscraptionCancled($sub)
    {
        $payment = payments::where('stripe_subscription_id', $sub->id)->first();
        if (!$payment) {
            Log::error('No payment found for subscription: ' . $sub->id);
            return;
        }

        SubscriptionModel::where('payment_id', $payment->id)->update([
            'status' => 'canceled',
            'end_at' => now()
        ]);
    }

    public function handlesubscraptionUpdated($sub)
    {
        $payment = payments::where('stripe_subscription_id', $sub->id)->first();
        if (!$payment) {
            Log::error('No payment found for subscription: ' . $sub->id);
            return;
        }

        SubscriptionModel::where('payment_id', $payment->id)->update([
            'status' => $sub->status,
        ]);
    }
}
