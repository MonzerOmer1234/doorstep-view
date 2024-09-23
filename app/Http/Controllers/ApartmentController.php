<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index()
    {
        return Apartment::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            // Add other validation rules as needed
        ]);

        return Apartment::create($request->all());
    }

    public function show($id)
    {
        return Apartment::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $apartment = Apartment::findOrFail($id);
        $apartment->update($request->all());
        return $apartment;
    }

    public function destroy($id)
    {
        Apartment::destroy($id);
        return response()->noContent();
    }

    // Method to feature apartments
    public function feature(Apartment $apartment)
    {
        $apartment->update(['featured' => true]);

        return redirect()->route('apartments.index')->with('success', 'Apartment featured successfully.');
    }
}
