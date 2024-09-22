<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //
        $users = User::all();

        return response()->json([
            'status' => 'success',
            'message' => 'All Users are fetched correctly',
            'users' => $users
        ] , 200);

    }

    /**
     * Store a newly created resource in storage.
     * @param Rquest $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            'name' => 'required |max:255',
            'email' => 'required |unique:users',
            'password' => 'required | min:8| confirmed',
            'phone_number' => ['required', 'phone'],
            'user_type' => 'required'
        ]);

        $user = User::create($fields);

        return response()->json([
            'status' => 'success',
            'message' => 'the user is created successfully',
            'user' => $user,
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
        $user = User::findOrFail($id);
        $fields = $request->validate([
            'name' => 'required |max:255',
            'email' => 'required |unique:users',
            'password' => 'required | min:8| confirmed',
            'phone_number' => ['required', 'phone'],
            'user_type' => 'required'
        ]);

        $user->update($fields);

        return response()->json([
            'status' => 'success',
            'message' => 'The user data is updated suucessfully',
            'user' => $user,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return Response
     */
    public function destroy(string $id)
    {
        //
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'The User is deleted Successfully',

        ] , 200);
    }
}
