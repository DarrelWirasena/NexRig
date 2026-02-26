<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // --- REGISTER ---
    public function showRegisterForm()
    {
        $title = 'Register';
        return view('auth.register', compact('title'));
    }

    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+\-]+@(gmail\.com|yahoo\.com|outlook\.com|icloud\.com|hotmail\.com)$/'
            ], // Email gak boleh kembar
            'password' => 'required|string|min:8|confirmed', // Harus ada input name="password_confirmation" di form
        ]);

        // 2. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' // Default user biasa
        ]);

        // 3. Langsung Login setelah daftar
        Auth::login($user);
        $redirect = $request->input('redirect');
        return redirect($redirect ?: route('home'))->with('success', 'Registration successful! Welcome to NexRig.');
    }

    // --- LOGIN ---
    public function showLoginForm()
    {
        $title = 'Login';
        return view('auth.login', compact('title'));
    }

   public function login(Request $request)
    {
        // 1. Validasi - HANYA email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'You are logged in!');
        }

        // 3. Gagal Login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Logged out successfully');
    }
}