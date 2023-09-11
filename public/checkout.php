<?php

require '../vendor/autoload.php';

use app\library\Cart;
use Stripe\StripeClient;

session_start();

$private_key = 'sk_test_51NjXWnC5G8eFxm2vcQBGU1qAZiampCLjrTgL0pbniBG4OqbrN2DPZh9R0noGTV2YZR0orUj3DWHoSFukQxUnVFKC004Vt5B9Um';

$stripe = new StripeClient($private_key);

$cart = new Cart;
$productsIncart = $cart->getCart();

$items = [
  'mode' => 'payment',
  'success_url' => 'http://localhost:80/cursophp/carrinhoDeCompras/public/success.php',
  'cancel_url' => 'http://localhost:80/cursophp/carrinhoDeCompras/public/cancel.php',
];

foreach ($productsIncart as $product) {
  $items['line_items'][] = [
    'price_data' => [
      'currency' => 'brl',
      'product_data' => [
        'name' => $product->getName()
      ],
      'unit_amount' => $product->getPrice() * 100
    ],
    'quantity' => $product->getQuantity()
  ];
}

$checkout_session = $stripe->checkout->sessions->create($items);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);