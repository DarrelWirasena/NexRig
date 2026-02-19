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
            'name' => 'required|string',   // Wajib ada untuk data email
            'email' => 'required|email',   // Wajib ada untuk data email
        ]);

        // 2. Simpan ke Database
        ContactMessage::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // 3. KIRIM EMAIL (Kode Baru)
        // Masukkan data ke array untuk dikirim ke Class Mail
        $emailData = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        // Kirim ke email Admin (Ganti dengan email tujuan Anda)
      // Kirim ke email Anda sendiri untuk testing
        Mail::to('pettyfervinn@gmail.com')->send(new ContactFormMail($emailData));

        // 4. Kembali dengan Sukses
        return redirect()->back()->with('success', 'Pesan tersimpan & Notifikasi email telah dikirim!');
    }
}