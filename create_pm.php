<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$stripeSecret = config('services.stripe.secret');

if (empty($stripeSecret)) {
    echo "ERROR: STRIPE_SECRET is not set in .env file\n";
    exit(1);
}

\Stripe\Stripe::setApiKey($stripeSecret);

try {
    $pm = \Stripe\PaymentMethod::create([
        'type' => 'card',
        'card' => [
            'token' => 'tok_visa',
        ],
    ]);

    echo "\n-----------------------------------------\n";
    echo "SUCCESS! Created Test Payment Method:\n";
    echo "Payment Method ID: " . $pm->id . "\n";
    echo "-----------------------------------------\n\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
