<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ItemApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Item::latest()->get();

        return response()->json([$items]);
    }

    public function show($id): JsonResponse
    {
        $item = Item::findOrFail($id);

        return response()->json($item);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:items,code',
            'stock' => 'required|integer|min:0',
        ]);

        $item = Item::create($validatedData);

        return response()->json([
            'message' => 'Item berhasil dibuat',
            'data' => $item
        ], 201);
    }
    
    public function update(Request $request, $id): JsonResponse
    {
        $item = Item::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:255|unique:items,code,' . $id,
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $item->update($validatedData);

        return response()->json($item);
    }

    public function destroy($id): JsonResponse
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json(null, 204);
    }

}
