<?php
namespace App\Services;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactService
{
public function storeMessage(array $data)
{
// Simpan pesan ke database
$message = ContactMessage::create([
'user_id' => Auth::id(),
'name' => $data['name'],
'email' => $data['email'],
'subject' => $data['subject'],
'message' => $data['message'],
]);

// Kirim email ke admin
$adminEmail = config('mail.admin_email');
if ($adminEmail) {
Mail::to($adminEmail)->send(new ContactFormMail($data));
}

return $message;
}

public function getUserMessages($userId)
{
return ContactMessage::where('user_id', $userId)->latest()->get();
}
}