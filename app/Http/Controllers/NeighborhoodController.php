<?php

namespace App\Http\Controllers;

use App\Models\Neighborhood;
use Illuminate\Http\Request;

class NeighborhoodController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //
        $neighborhoods = Neighborhood::all();
        return response()->json([
            'status' => 'success',
            'message' =>'The neighborhoods are fetched successfully',
            'neighborhoods' => $neighborhoods
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
            'name' => 'required',
            'description' => 'required',
        ]);

        $neighborhood = Neighborhood::create($fields);
        return response()->json([
            'status' => 'success',
            'message' => 'The neighborhood is added successfully',
            'neighborhood' => $neighborhood,
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
        $neighborhood = Neighborhood::findOrFail($id);
        $fields = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $neighborhood->update($fields);
        return response()->json([
            'status' => 'success',
            'message' => 'The neighborhood is updated successfully',
            'neighborhood' => $neighborhood
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
        $neighborhood = Neighborhood::findOrFail($id);
        $neighborhood->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'The neighborhood is deleted successfully',

        ] , 200);
    }
}
