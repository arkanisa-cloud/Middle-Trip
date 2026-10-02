<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingPoint;
use App\Models\Mountain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeetingPointController extends Controller
{
    /**
     * Display a listing of the meeting points.
     */
    public function index(Request $request): View
    {
        $mountainId = $request->query('mountain_id');

        $meetingPoints = MeetingPoint::with('mountain')
            ->when($mountainId, fn ($q) => $q->where('mountain_id', $mountainId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $mountains = Mountain::orderBy('name')->get();

        return view('admin.meeting-points.index', compact('meetingPoints', 'mountains', 'mountainId'));
    }

    /**
     * Store a newly created meeting point in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mountain_id' => 'required|exists:mountains,id',
            'name' => 'required|string|max:150',
            'location_type' => 'required|in:basecamp,station,airport,terminal',
            'additional_price_per_pax' => 'required|integer|min:0',
            'is_default' => 'nullable|boolean',
        ]);

        MeetingPoint::create([
            'mountain_id' => $validated['mountain_id'],
            'name' => $validated['name'],
            'location_type' => $validated['location_type'],
            'additional_price_per_pax' => $validated['additional_price_per_pax'],
            'is_default' => $request->boolean('is_default', false),
        ]);

        return redirect()->route('admin.meeting-points.index')->with('success', 'Titik kumpul shuttle berhasil ditambahkan.');
    }

    /**
     * Update the specified meeting point in storage.
     */
    public function update(Request $request, MeetingPoint $meetingPoint): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'location_type' => 'required|in:basecamp,station,airport,terminal',
            'additional_price_per_pax' => 'required|integer|min:0',
            'is_default' => 'nullable|boolean',
        ]);

        $meetingPoint->update([
            'name' => $validated['name'],
            'location_type' => $validated['location_type'],
            'additional_price_per_pax' => $validated['additional_price_per_pax'],
            'is_default' => $request->boolean('is_default', false),
        ]);

        return redirect()->route('admin.meeting-points.index')->with('success', 'Titik kumpul shuttle berhasil diperbarui.');
    }

    /**
     * Remove the specified meeting point from storage.
     */
    public function destroy(MeetingPoint $meetingPoint): RedirectResponse
    {
        $meetingPoint->delete();

        return redirect()->route('admin.meeting-points.index')->with('success', 'Titik kumpul berhasil dihapus.');
    }
}
