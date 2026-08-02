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
use Stripe\PaymentIntent;
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
        $company = auth()->user()->company;
        Stripe::setApiKey(config('services.stripe.secret'));
        if (!$company->stripe_customer_id) {
            $customer = Customer::create([
                'name' => $company->company_name,
                'email' => auth()->user()->email
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
        $plan = Plan::findOrFail($request->plan_id);

        $company = auth()->user()->company;

        Stripe::setApiKey(config('services.stripe.secret'));


        $subscription = Subscription::create([
            'customer' => $company->stripe_customer_id,

            'items' => [
                [
                    'price' => $plan->stripe_price_id,
                ]
            ],

            'default_payment_method' => $request->payment_method_id,
                'payment_behavior' => 'default_incomplete',
    'expand' => ['latest_invoice.payment_intent'],
        ]);
$invoice=$subscription->latest_invoice;

        $payment = payments::create([
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'stripe_payment_intent_id' =>null ,
            'stripe_subscription_id' => $subscription->id,
            'amount' => $plan->price,
            'currency' => 'egp',
            'status' => 'pending'
        ]);

        return apiResponse(200, 'Subscription created', [
            'subscription_id' => $subscription->id,
            'status' => $subscription->status,
            'client_secret' => $invoice->confirmation_secret->client_secret ?? NULL,

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
            Log::error('error in stripe: ' . $event->type);
        }
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function handleInvoicesSuccess($invoice)
    {
        $payment = payments::where('stripe_subscription_id', $invoice->subscription)->first();
        if (!$payment) {
            return Log::error('no payment found');
        }
        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);
            $subscription = SubscriptionModel::firstOrCreate(['payment_id' => $payment->id], [
                'company_id' => $payment->company_id,
                'plan_id' => $payment->plan_id,
                'payment_id' => $payment->id,
                'start_at' => now(),
                'end_at' => now()->addMonth(),
                'status' => 'active',
            ]);
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
            return Log::error('no payment found');
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
            return Log::error('no payment found');
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
            return Log::error('no payment found');
        }
        SubscriptionModel::where('payment_id', $payment->id)->update([
            'status' => $sub->status,
        ]);
    }
}
