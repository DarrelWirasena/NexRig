<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // <--- PENTING: Import Mail
use App\Mail\ContactFormMail;        // <--- PENTING: Import Mailable

class ContactController extends Controller
{

public function index()
{
    $title = 'Contact Support History';
    $history = ContactMessage::where('user_id', Auth::id()) // Menggunakan A besar
                             ->latest()
                             ->get();

    return view('support-history', compact('history', 'title'));
}
    public function store(Request $request)
{
    // 1. Validasi
    $validated = $request->validate([
        'subject' => 'required|string',
        'message' => 'required|string',
        'name' => 'required|string',
        'email' => 'required|email',
    ]);

    // 2. Simpan ke Database
    ContactMessage::create([
        'user_id' => Auth::id(),
        'name' => $request->name,
        'email' => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
    ]);

    // 3. Kirim Email
    $emailData = [
        'name' => $request->name,
        'email' => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
    ];

    $adminEmail = config('mail.admin_email');
    if (!$adminEmail) {
        return redirect()->back()->with('error', 'Mail configuration missing.');
    }
    Mail::to($adminEmail)->send(new ContactFormMail($emailData));

    // 4. Redirect
        return redirect()->back()->with('success', 'Pesan tersimpan & Notifikasi email telah dikirim!');
    }
}