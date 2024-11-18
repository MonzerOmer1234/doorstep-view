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
                new OA\Property(property: 'message', type: 'string', example: 'Paasword reset link sent! | failed to send reset link'),


            ]
        )
    )]
    public function forgotPassword(Request $request){




    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    // Generate a 4-digit OTP
    $otp = rand(1000, 9999);
    $email = $request->email;

    // Save OTP and token in password_resets table
    $token = Hash::make($otp); // Token hashed for security
    DB::table('password_resets')->updateOrInsert(
        ['email' => $email],
        ['token' => $token, 'otp' => $otp, 'created_at' => now()]
    );

    // Inline email content (plain text or HTML)
    $emailContent = "
        Hello,

        Your OTP for resetting your password is:
        $otp

        Please use this OTP to reset your password. It is valid for a limited time.

        Thank you!
    ";

    try{

        // Send OTP via email
        set_time_limit(120);
        Mail::raw($emailContent, function ($message) use ($email) {
            $message->to($email)->subject('Reset Password OTP');
        });
    } catch(Exception $e){
        Log::error('Email sending failed: ' . $e->getMessage());
        return response()->json(['message' => 'Failed to send OTP, please try again later.'], 500);
    }


    return response()->json([
        'message' => 'OTP sent to your email',
        'token' => $token, // Return hashed token to be used by the user
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
                    new OA\Property(property: 'token', type: 'string', format: 'password', example: 'mmmmmmxcvveccvvrv'),



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
                new OA\Property(property: 'message', type: 'string', example: 'Password reset successful! | Failed to reset password.'),



            ]
        )
    )]

 public function resetPassword(Request $request)
{
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Check if OTP matches
        $resetRequest = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$resetRequest || $resetRequest->otp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete reset request
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
}
