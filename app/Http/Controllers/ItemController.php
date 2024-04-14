<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return response()->json($items);
    }
    public function uploadImage(Request $request)
    {
        // Validate the request (e.g., check if a file was uploaded)
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the uploaded image in the 'mongodb' disk
        $path = $request->file('image')->store('images', 'mongodb');

        // Return the path to the stored image
        return response()->json(['path' => $path]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $request->file('image')->store('item_images');

        $item = new Item([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'image' => $imagePath,
        ]);
        $item->save();

        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ]);

        $item = Item::findOrFail($id);
        $item->update($validatedData);

        return response()->json($item, 200);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json(null, 204);
    }
}
