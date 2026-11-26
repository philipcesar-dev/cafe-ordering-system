<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google's OAuth page
     */
    public function redirect()
    {
        return Socialite::driver('google')
        ->with(['prompt' => 'select_account consent'])
        ->redirect();
    }

    /**
     * Handle the callback from Google
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user by google_id or email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                // Update google_id and avatar if needed
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                // Register new user
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'password'  => Hash::make(uniqid()), // random password
                    'is_admin'  => false,
                ]);
            }

            // Log the user in
            Auth::login($user, true);

            // Redirect based on role
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Successfully logged in with Google!');
            }

            return redirect()->route('home')
                ->with('success', 'Successfully logged in with Google!');

        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again.'.$e->getmessage());
        }
    }
}