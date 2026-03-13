<?php
namespace App\Services;

use Midtrans\Snap;

class MidtransService
{
public function processPayment($order)
{
$params = [
'transaction_details' => [
'order_id' => $order->id,
'gross_amount' => $order->total,
],
// Add other Midtrans parameters here...
];
return Snap::createTransaction($params);
}
}