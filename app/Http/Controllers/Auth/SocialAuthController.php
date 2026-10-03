<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and authenticate.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal melakukan otentikasi Google: '.$e->getMessage(),
            ]);
        }

        if (! $googleUser->getEmail()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Google Anda tidak memiliki alamat email yang valid.',
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $user->avatar ?: $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Pendaki MiddleTrip'),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'is_admin' => false,
            ]);

            event(new Registered($user));
        }

        Auth::login($user, true);

        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home', absolute: false));
    }
}
