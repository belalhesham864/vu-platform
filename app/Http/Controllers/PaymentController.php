<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Models\payments;
use App\Models\Plan;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
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
        $paymentintent = PaymentIntent::create([
            'amount' => $plan->price * 100,
            'currency' => strtolower($plan->currency)
        ]);
        $payment = payments::create([
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'stripe_payment_intent_id' => $paymentintent->id,
            'amount' => $plan->price,
            'status' => 'pending',

        ]);
        return apiResponse(200, 'Payment created', [
            'payment_id' => $payment->id,
            'client_secret' => $paymentintent->client_secret,
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

        if ($event->type == 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $payment = payments::where('stripe_payment_intent_id', $paymentIntent->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'success',
                ]);
            }
        } elseif ($event->type == 'payment_intent.payment_failed') {
            $paymentIntent = $event->data->object;
            $payment = payments::where('stripe_payment_intent_id', $paymentIntent->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                ]);
            }
        }
        return response()->json([
            'status' => 'success'
        ]);
    }
}
