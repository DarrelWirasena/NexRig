<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function edit()
    {
        return $this->view('profile.app', 'My Profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name'             => 'required|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password'     => 'nullable|min:8|different:current_password|confirmed',
        ]);

        try {
            $this->profileService->updateProfile($validatedData);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}