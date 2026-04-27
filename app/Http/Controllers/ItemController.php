<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(): View
    {
        $items = Item::latest()->get();

        return view('admin.items.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:buku,alat',
            'code' => 'nullable|string|max:255|unique:items,code',
            'stock' => 'required|integer|min:0',
        ]);

        $validated['code'] = $validated['code'] ?: $this->generateItemCode($validated['type']);

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item): View
    {
        return view('admin.items.edit', compact('item'));
    }

    public function show(Item $item): RedirectResponse
    {
        return redirect()->route('items.edit', $item);
    }

    public function print(Item $item): View
    {
        return view('admin.items.print', compact('item'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:buku,alat',
            'code' => 'nullable|string|max:255|unique:items,code,' . $item->id,
            'stock' => 'required|integer|min:0',
        ]);

        $validated['code'] = $validated['code'] ?: $item->code ?: $this->generateItemCode($validated['type']);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function generateItemCode(string $type): string
    {
        $prefix = $type === 'alat' ? 'ALT' : 'BKU';

        do {
            $code = $prefix . '-' . Str::upper(Str::random(6));
        } while (Item::where('code', $code)->exists());

        return $code;
    }
}
