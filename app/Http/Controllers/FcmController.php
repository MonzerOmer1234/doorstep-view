<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use openApi\Attributes as OA;


class FcmController extends Controller
{
    // method the updates device token
    /**
     * @param Request $request
     * @return Response
     */
    #[OA\Put(
        path: '/api/update-device-token',
        description: 'Update device token',
        tags: ['Update Device Token'],
        security : [["bearerAuth" => []]],

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                    new OA\Property(property: 'fcm_token', type: 'string', format: 'email', example: 'xxxaadddff'),

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
        description: 'Update device token',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(property: 'message', type: 'string', example: 'Device token is updated successfully!'),


            ]
        )
    )]
    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fcm_token' => 'required|string',
        ]);

        try {
            $user = \App\Models\User::findOrFail($request->user_id);
            $user->update(['fcm_token' => $request->fcm_token]);

            Log::info('Device token updated', [
                'user_id' => $request->user_id,
                'token' => substr($request->fcm_token, 0, 10) . '...' // Log only part of the token for security
            ]);

            return response()->json(['message' => 'Device token updated successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to update device token', [
                'user_id' => $request->user_id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Failed to update device token'], 500);
        }
    }

    // send firebase cloud message notification
    /**
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/send-fcm-notification',
        description: 'Send firebase cloud message notification',
        tags: ['Firebase Notification'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                    new OA\Property(property: 'title', type: 'string', format: 'email', example: 'notification 1'),
                    new OA\Property(property: 'body', type: 'string', format: 'email', example: 'message 1'),

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
        response: 200,
        description: 'Send firebase cloud message notification',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(property: 'message', type: 'string', example: 'Notification is sent successfully!'),


            ]
        )
    )]

    public function sendFcmNotification(Request $request)
    {
        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        // Get user and check FCM token
        $user = \App\Models\User::findOrFail($request->user_id);
        if (!$user->fcm_token) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }

        // Check Firebase credentials file
        $credentialsPath = storage_path('app/credentials/doorstep-view-firebase-adminsdk-6f2bi-373e7e8f2b.json');
        if (!file_exists($credentialsPath)) {
    Log::error('Firebase credentials file missing at: ' . $credentialsPath);
    return response()->json(['message' => 'Firebase configuration error'], 500);
      }

        try {
            // Initialize Google Client


            $client = new GoogleClient();
            $client->useApplicationDefaultCredentials();
            $client->setAuthConfig($credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $accessToken = $client->getAccessToken()['access_token'];

            // Prepare notification data
            $projectId = config('services.fcm.project_id', 'doorstep-vujade');
            $data = [
                'message' => [
                    'token' => $user->fcm_token,
                    'notification' => [
                        'title' => $request->title,
                        'body' => $request->body,
                    ],
                    // Optional: Add data payload if needed
                    'data' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'notification'
                    ]
                ]
            ];

            // Send notification using Laravel's HTTP client
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post(
                "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                $data
            );

            // Handle response
            if ($response->successful()) {
                return response()->json([
                    'message' => 'Notification sent successfully',
                    'response' => $response->json()
                ]);
            }

            // Log error if request fails
            Log::error('FCM Notification failed', [
                'error' => $response->body(),
                'status' => $response->status()
            ]);

            return response()->json([
                'message' => 'Failed to send notification',
                'error' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('FCM Notification error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'message' => 'Error sending notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
