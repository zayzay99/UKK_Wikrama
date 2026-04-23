<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'code' => 'required|string|max:255|unique:items,code',
            'stock' => 'required|integer|min:0',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item): View
    {
        return view('admin.items.edit', compact('item'));
    }

    public function show(Item $item): RedirectResponse
    {
        return redirect()->route('items.edit', $item);
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:buku,alat',
            'code' => 'required|string|max:255|unique:items,code,' . $item->id,
            'stock' => 'required|integer|min:0',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
