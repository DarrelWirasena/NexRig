<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends ApiController
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index(): JsonResponse
    {
        $messages = $this->contactService->getUserMessages(auth()->id());

        return $this->successResponse($messages, 'Contact messages retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
            'name'    => 'required|string',
            'email'   => 'required|email',
        ]);

        $message = $this->contactService->storeMessage($request->all());

        return $this->createdResponse($message, 'Message sent successfully.');
    }
}