<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Method that handles the registration of a user.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/auth/register',
        description: 'registering a user',
        tags: ['register'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'email', example: '2345677uu'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'email', example: '2345677uu'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '+249961077805'),
                    new OA\Property(property: 'user_type', type: 'string', format: 'email', example: '2'),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'User is registered successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'token', type: 'string', example: 'adcxzvbhfredfgh'),

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
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'phone_number' => ['required', 'phone' , 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'user_type' => ['required'],
        ]);

        $user = User::create($fields);
        FacadesAuth::login($user);
        $token = $user->createToken($request->name)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Method that handles the login of a user.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/auth/login',
        description: 'log a user',
        tags: ['login'],
        security : [["bearerAuth" => []]],

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [

                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'email', example: '2345677uu'),

                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'User is logged in successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'token', type: 'string', example: 'adcxzvbhfredfgh'),

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

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'exists:users'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'These are not valid credentials',
            ], 401);
        }

        $token = $user->createToken('apitoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Method that handles the logout of a user.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/auth/logout',
        description: 'user is logging out',
        tags: ['logout'],
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
        description: 'User is logged out successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'you are logged out successfully'),


            ]
        )
    )]
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'You are logged out',
        ], 200);
    }

    /**
     * Method to initiate the password reset process.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/auth/password/forgot',
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
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            // Set a longer timeout just for this operation
            set_time_limit(120);

            // Verify mail configuration before attempting to send
            if (!config('mail.mailers.smtp.host') || !config('mail.mailers.smtp.port')) {
                Log::error('Mail configuration is incomplete');
                return response()->json([
                    'message' => 'Unable to send reset link due to mail configuration issues',
                    'debug_message' => config('app.debug') ? 'Mail configuration is missing or incomplete' : null
                ], 500);
            }

            Log::info('Attempting to send password reset email to: ' . $request->email);

            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                Log::info('Password reset link sent successfully to: ' . $request->email);
                return response()->json(['message' => __('Password reset link sent!')], 200);
            } else {
                Log::error('Failed to send reset link. Status: ' . $status);
                return response()->json([
                    'message' => 'Unable to send password reset email',
                    'debug_message' => config('app.debug')
                        ? 'Please verify your Mailtrap configuration in .env file: MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD'
                        : null
                ], 500);
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $isConnectionError = str_contains($errorMessage, 'Connection could not be established');

            Log::error('Password reset email failed: ' . $errorMessage, [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Failed to send reset link. Please try again later.',
                'debug_message' => config('app.debug')
                    ? ($isConnectionError
                        ? 'Failed to connect to mail server. Please check your Mailtrap credentials and network connection.'
                        : $errorMessage)
                    : null
            ], 500);
        }
    }

    /**
     * Method to reset the user's password.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/auth/password/reset',

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
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
            'password_confirmation' => 'required' // Add this validation
    ]);



        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __('Password reset successful!')], 200)
            : response()->json(['message' => __('Failed to reset password.')], 500);
    }
}
