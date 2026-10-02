<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil pengguna dan riwayat pesanan ekspedisi.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Otomatis kaitkan pesanan lama yang belum ada user_id namun menggunakan email yang sama
        Booking::whereNull('user_id')
            ->where('customer_email', $user->email)
            ->update(['user_id' => $user->id]);

        $bookings = Booking::where('user_id', $user->id)
            ->with([
                'expedition.mountain.routes',
                'route.mountain',
                'meetingPoint',
                'participants',
                'addons',
                'paymentTransactions',
            ])
            ->latest()
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'bookings' => $bookings,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
