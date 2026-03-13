<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\BrevoMailService;

class AuthService
{
public function register(array $data)
{
$otp = random_int(100000, 999999);

$user = User::create([
'name' => $data['name'],
'email' => $data['email'],
'password' => Hash::make($data['password']),
'role' => 'user',
'otp_code' => $otp,
'otp_expires_at' => now()->addMinutes(10),
]);

try {
app(BrevoMailService::class)->sendOtpEmail(
$user->email,
$user->name,
$otp
);
} catch (\Exception $e) {
Log::error('Failed to send OTP email during registration', [
'email' => $user->email,
'error' => $e->getMessage(),
]);
}

return $user;
}

public function verifyOtp(string $email, string $otp)
{
$user = User::where('email', $email)
->where('otp_code', $otp)
->where('otp_expires_at', '>', now())
->first();

if ($user) {
$user->update([
'email_verified_at' => now(),
'otp_code' => null,
'otp_expires_at' => null,
]);

Auth::login($user);
return $user;
}

throw new \Exception('Invalid or expired OTP.');
}

public function login(array $credentials)
{
if (Auth::attempt($credentials)) {
session()->regenerate();
return Auth::user();
}

throw new \Exception('Invalid email or password.');
}

public function logout()
{
Auth::logout();
session()->invalidate();
session()->regenerateToken();
}
}