<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    //
       /**
     * Method to initiate the password reset process.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/password/forgot',
        description: 'forgetting password',
        tags: ['Forgot Password'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [

                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),


                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Forgotting password',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Otp is sent successfully'),
                new OA\Property(property: 'otp_token', type: 'integer', example: 1234),


            ]
        )
    )]
    public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    // Find the user by email
    $user = User::where('email', $request->email)->first();

    // Generate a 4-digit OTP
    $otp = random_int(1000, 9999);

    // Hash the OTP for secure storage
    $hashedOtp = Hash::make($otp);

    // Store OTP in the password_resets table
    DB::table('password_resets')->updateOrInsert(
        ['email' => $user->email],
        [
            'token' => $hashedOtp,
            'created_at' => now(),
        ]
    );

    // Send the OTP to the user's email
    Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
        $message->to($user->email)
            ->subject('Your OTP for Login');
    });

    return response()->json([
        'message' => 'OTP sent successfully. Use this token to log in.',
        'otp_token' => $otp, // Return plain text OTP to the user
    ]);
}



    /**
     * Method to reset the user's password.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/password/reset',

        description: 'reset password',
        tags: ['Resetting Password'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [

                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'mmmmmmmm'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'mmmmmmmm'),
                    new OA\Property(property: 'otp', type: 'integer', example: 1234), // Ensure items is defined correctly



                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Resetting password',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Password is resetted successfully
                + , you can log in!'),
                new OA\Property(property: 'otp', type: 'string', example: 'xxxxxx'),



            ]
        )
    )]

 public function resetPassword(Request $request)
{



        // Check if OTP matches

    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
        'otp' => 'required|numeric',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid email.'], 401);
    }

    $passwordReset = DB::table('password_resets')
        ->where('email', $request->email)
        ->first();

    if (!$passwordReset || !Hash::check($request->otp, $passwordReset->token)) {
        return response()->json(['message' => 'Invalid OTP.'], 400);
    }


     // Update the user's password
     $user->password = Hash::make($request->password);
     $user->save();
    // Generate and return a login token
    $token = $user->createToken('authToken')->plainTextToken;

    // Invalidate OTP
    DB::table('password_resets')->where('email', $request->email)->delete();

    return response()->json([
        'message' => 'Login successful.',
        'token' => $token,
    ]);
}

    }

