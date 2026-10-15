<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $previous = url()->previous();

        if (! session()->has('url.intended') && ! $this->isGuestOrDashboardUrl($previous)) {
            session()->put('url.intended', $previous);
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $intended = session()->get('url.intended');
        if ($this->isGuestOrDashboardUrl($intended)) {
            session()->forget('url.intended');
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Check if a given URL is a guest route, auth route, or dashboard.
     */
    public static function isGuestOrDashboardUrl(?string $url): bool
    {
        if (! $url) {
            return true;
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $normalizedPath = trim($path, '/');

        $blacklisted = [
            '',
            'login',
            'register',
            'forgot-password',
            'reset-password',
            'dashboard',
            'auth/google',
            'auth/google/callback',
        ];

        foreach ($blacklisted as $item) {
            if ($item === '' && $normalizedPath === '') {
                return false; // Root '/' is valid
            }
            if ($item !== '' && ($normalizedPath === $item || str_starts_with($normalizedPath, $item.'/'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
