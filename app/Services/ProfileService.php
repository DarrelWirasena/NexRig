<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileService
{
    /**
     * Update the authenticated user's profile.
     *
     * @param array $data
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(array $data): void
    {
        /** @var User $user */
        $user = Auth::user();

        // Update name
        $user->name = $data['name'];

        // Update password if provided
        if (!empty($data['new_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                throw new \Illuminate\Validation\ValidationException(null, [
                    'current_password' => 'Password saat ini salah.',
                ]);
            }

            $user->password = Hash::make($data['new_password']);
        }

        $user->save();
    }
}
