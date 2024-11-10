<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use openapi\Attributes as OA;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    #[OA\Get(
        path: '/api/users',
        description: 'getting all Users',
        tags: ['All Users'],
        security : [["bearerAuth" => []]],

    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Fetching all Users',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Users are fetched successfully!'),

                new OA\Property(
                    property: 'users',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com')
                    ]
                )
            ]
        )
    )]
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
    #[OA\Post(
        path: '/api/users',
        description: 'Create a user',
        tags: ['Create a user'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string',  example: 'john.doe@example.com'),
                    new OA\Property(property: 'password_confirmation', type: 'string',  example: 'john.doe@example.com'),
                    new OA\Property(property: 'phone_number', type: 'string',  example: 'john.doe@example.com'),
                    new OA\Property(property: 'user_type', type: 'string',  example: 'john.doe@example.com'),
                    ]
            )
        )
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 201,
        description: 'Create a user',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'User is created successfully!'),

                new OA\Property(
                    property: 'user',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com')
                    ]
                )
            ]
        )
    )]
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'user_type' => $request->user_type,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'the user is created successfully',
            'user' => $user,
        ] , 201);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return Response
     */
    #[OA\Patch(
        path: '/api/users/{user}',
        description: 'Update a user',
        tags: ['Update a user'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "user",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'phone_number', type: 'string',  example: '+201158972855'),
                    new OA\Property(property: 'password', type: 'string',  example: 'john.doe@example.com'),
                    new OA\Property(property: 'user_type', type: 'string',  example: '4'),
                ]
            )
        )
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]


    #[OA\Response(
        response: 201,
        description: 'Update a user',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'User is updated successfully!'),

                new OA\Property(
                    property: 'user',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe John'),
                        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com')
                    ]
                )
            ]
        )
    )]

    public function update(Request $request, string $id)
    {
        //
        $user = User::findOrFail($id);
        $fields = $request->validate([
            'name' => 'required |max:255',
            'email' => 'required |unique:users',
            'password' => 'required | min:8',
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
    #[OA\Delete(
        path: '/api/users/{user}',
        description: 'Delete a user',
        tags: ['Delete a user'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "user",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )],
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 201,
        description: 'Delete a user',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'User is deleted successfully!'),


            ]
        )
    )]
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
