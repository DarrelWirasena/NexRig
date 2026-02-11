<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Variabel untuk menampung data pesan

    // 1. Terima data saat class dipanggil
    public function __construct($data)
    {
        $this->data = $data;
    }

    // 2. Bangun Emailnya
    public function build()
    {
        return $this->subject('Pesan Baru dari: ' . $this->data['name']) // Subjek Email
                    ->replyTo($this->data['email']) // Agar Admin bisa langsung reply ke user
                    ->view('emails.contact'); // File tampilan (yang akan kita buat di langkah 3)
    }
}