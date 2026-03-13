<?php

namespace App\Http\Controllers;

use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $history = $this->contactService->getUserMessages(Auth::id());

        return $this->view('support-history', 'Contact Support History', compact('history'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
            'name'    => 'required|string',
            'email'   => 'required|email',
        ]);

        $this->contactService->storeMessage($request->all());

        return back()->with('success', 'Pesan tersimpan & Notifikasi email telah dikirim!');
    }
}