<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;

class MidtransController extends ApiController
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function processPayment(int $orderId): JsonResponse
    {
        $order = Order::find($orderId);

        if (!$order) {
            return $this->notFoundResponse('Order not found');
        }

        $payment = $this->midtransService->processPayment($order);

        return $this->successResponse($payment, 'Payment processed successfully');
    }
}