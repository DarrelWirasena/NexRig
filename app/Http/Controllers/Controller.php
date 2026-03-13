<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Redirect dengan pesan sukses
     */
    protected function redirectSuccess(string $route, string $message, array $params = [])
    {
        return redirect()->route($route, $params)->with('success', $message);
    }

    /**
     * Redirect dengan pesan error
     */
    protected function redirectError(string $message)
    {
        return redirect()->back()->with('error', $message);
    }

    /**
     * Return view dengan title
     */
    protected function view(string $view, string $title, array $data = [])
    {
        return view($view, array_merge(['title' => $title], $data));
    }

    /**
     * JSON error response — dipakai oleh Web controllers yang handle AJAX
     */
    protected function errorResponse(string $message, int $statusCode = 400, $errors = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
            'data'    => null,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $statusCode);
    }
}