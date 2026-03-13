<?php
namespace App\Services;

use App\Models\Order;

class OrderService
{
public function createOrder($data)
{
$order = Order::create($data);
// Add additional order creation logic here (e.g., send notifications, update stock, etc.)
return $order;
}

public function getOrderById($id)
{
return Order::find($id);
}
}