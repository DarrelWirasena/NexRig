<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function createOrder(Request $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->all());

        return $this->createdResponse($order, 'Order created successfully');
    }

    public function getOrderDetails(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);

        if (!$order) {
            return $this->notFoundResponse('Order not found');
        }

        return $this->successResponse($order, 'Order details retrieved successfully');
    }
}