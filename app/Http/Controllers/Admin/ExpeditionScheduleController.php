<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expedition;
use App\Models\Mountain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExpeditionScheduleController extends Controller
{
    /**
     * Display a listing of expedition schedules.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $type = $request->query('type');
        $mountainId = $request->query('mountain_id');

        $expeditions = Expedition::with(['mountain', 'route'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($mountainId, fn ($q) => $q->where('mountain_id', $mountainId))
            ->orderBy('departure_date', 'asc')
            ->paginate(12)
            ->withQueryString();

        $mountains = Mountain::orderBy('name')->get();

        return view('admin.expeditions.index', compact('expeditions', 'mountains', 'status', 'type', 'mountainId'));
    }

    /**
     * Show the form for creating a new expedition batch.
     */
    public function create(Request $request): View
    {
        $selectedMountainId = $request->query('mountain_id');
        $mountains = Mountain::with('routes')->where('is_active', true)->orderBy('name')->get();

        return view('admin.expeditions.create', compact('mountains', 'selectedMountainId'));
    }

    /**
     * Store a newly created expedition in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mountain_id' => 'required|exists:mountains,id',
            'route_id' => 'required|exists:routes,id',
            'type' => 'nullable|in:open,private',
            'hiking_type' => 'required|in:camping,tektok',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:departure_date',
            'quota_max' => 'required|integer|min:1',
            'status' => 'required|in:open,price_locked,completed,cancelled',
        ]);

        Expedition::create([
            'mountain_id' => $validated['mountain_id'],
            'route_id' => $validated['route_id'],
            'type' => $validated['type'] ?? 'open',
            'hiking_type' => $validated['hiking_type'],
            'departure_date' => $validated['departure_date'],
            'return_date' => $validated['return_date'],
            'quota_max' => $validated['quota_max'],
            'quota_booked' => 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.expeditions.index')->with('success', 'Batch jadwal ekspedisi berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified expedition.
     */
    public function edit(Expedition $expedition): View
    {
        $mountains = Mountain::with('routes')->get();

        return view('admin.expeditions.edit', compact('expedition', 'mountains'));
    }

    /**
     * Update the specified expedition in storage.
     */
    public function update(Request $request, Expedition $expedition): RedirectResponse
    {
        $validated = $request->validate([
            'mountain_id' => 'required|exists:mountains,id',
            'route_id' => 'required|exists:routes,id',
            'type' => 'nullable|in:open,private',
            'hiking_type' => 'required|in:camping,tektok',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:departure_date',
            'quota_max' => 'required|integer|min:1',
            'status' => 'required|in:open,price_locked,completed,cancelled',
        ]);

        $validated['type'] = $validated['type'] ?? 'open';
        $expedition->update($validated);

        return redirect()->route('admin.expeditions.index')->with('success', 'Batch jadwal ekspedisi berhasil diperbarui.');
    }

    /**
     * Trigger manual Price Lock for an open batch.
     */
    public function triggerPriceLock(Expedition $expedition): RedirectResponse
    {
        if ($expedition->status !== 'open') {
            return back()->with('error', 'Hanya batch berstatus Open yang dapat dikunci harganya.');
        }

        DB::transaction(function () use ($expedition) {
            $lockedPrice = $expedition->mountain->getTierPriceForPax($expedition->quota_booked);

            $expedition->update([
                'current_locked_price' => $lockedPrice,
                'status' => 'price_locked',
            ]);

            // Update all reserved bookings in this expedition
            $deadline = now()->addHours(48);
            foreach ($expedition->bookings()->where('status', 'reserved')->get() as $booking) {
                $shuttleTotal = $booking->shuttle_fee_total;
                $addonsTotal = $booking->addons_fee_total;
                $paxTotal = $booking->pax_count * $lockedPrice;
                $grandTotal = $paxTotal + $shuttleTotal + $addonsTotal;
                $remaining = max(0, $grandTotal - $booking->total_booking_fee);

                $booking->update([
                    'locked_price_per_pax' => $lockedPrice,
                    'grand_total' => $grandTotal,
                    'remaining_payment_total' => $remaining,
                    'payment_deadline' => $deadline,
                    'status' => 'price_locked',
                ]);
            }
        });

        return back()->with('success', 'Price Lock berhasil dieksekusi! Tarif final telah dikunci dan tagihan pelunasan diterbitkan.');
    }

    /**
     * Remove the specified expedition from storage.
     */
    public function destroy(Expedition $expedition): RedirectResponse
    {
        if ($expedition->bookings()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus batch yang sudah memiliki data pemesanan.');
        }

        $expedition->delete();

        return redirect()->route('admin.expeditions.index')->with('success', 'Batch jadwal berhasil dihapus.');
    }
}
