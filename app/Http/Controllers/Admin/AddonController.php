<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddonController extends Controller
{
    /**
     * Display a listing of the addons.
     */
    public function index(): View
    {
        $addons = Addon::latest()->paginate(15);

        return view('admin.addons.index', compact('addons'));
    }

    /**
     * Store a newly created addon in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'price' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        Addon::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.addons.index')->with('success', 'Addon perlengkapan sewa berhasil ditambahkan.');
    }

    /**
     * Update the specified addon in storage.
     */
    public function update(Request $request, Addon $addon): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'price' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $addon->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.addons.index')->with('success', 'Addon perlengkapan berhasil diperbarui.');
    }

    /**
     * Remove the specified addon from storage.
     */
    public function destroy(Addon $addon): RedirectResponse
    {
        $addon->delete();

        return redirect()->route('admin.addons.index')->with('success', 'Addon berhasil dihapus.');
    }
}
