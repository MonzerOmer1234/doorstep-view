<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //
        $apartments = Apartment::all();
        return response()->json([
            'status' => 'success',
            'message' =>'All apartments are fetched successfully',
            'apartments' => $apartments
        ] , 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' =>'required',
            'address'=> 'required',
            'user_id' => 'required',
        ]);
        $apartment = Apartment::create($fields);

        return response()->json([
            'status' => 'success',
            'message' => 'The apartment is added successfully',
            'apartment' => $apartment
        ] , 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function update(Request $request, string $id)
    {
        //
        $apartment = Apartment::findOrFail($id);
        $fields = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' =>'required',
            'address'=> 'required',
            'user_id' => 'required',
        ]);
        $apartment->update($fields);
        return response()->json([
            'status' => 'success',
            'message' => 'The apartment is updated successfully',
            'apartment' => $apartment
        ] , 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return Response
     */
    public function destroy(string $id)
    {
        //
        $apartment = Apartment::findOrFail($id);
        $apartment->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'The apartment is deleted successfully',

        ] , 200);
    }
}
