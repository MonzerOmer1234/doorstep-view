<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
     /**
     * method that handles the registeration of user
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        // validation

        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'phone_number' => ['required', 'phone'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'user_type' => ['required'],
        ]);

        // create the user
        $user = User::create($fields);

        // login the user

        FacadesAuth::login($user);

        // generate Token

        $token = $user->createToken($request->name)->plainTextToken;

        //    return the response

        // returning responses work like following
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);

        //  return [
        //     'user' => $user,
        //     'token' => $token,
        //  ];
    }
        /**
     * method that handles the login of user
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        // validate
        $fields = $request->validate([
            'email' => ['required', 'exists:users'],
            'password' => ['required']
        ]);
        // check the email and password
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // convert array to Response
            return response()->json([
                'message' => 'these are not valid credentials',
            ], 401,);
        }
        $token = $user->createToken('apitoken')->plainTextToken;
        //    return response
        // convert array to Response

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200,);
    }
    /**
     * method that handles the logout of user
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'You are logged out',
        ], 200,);
    }
}
