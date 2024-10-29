<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
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
        tags: ['Update Device Token']
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

        $request->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'Device token updated successfully']);
    }

    // send firebase cloud message notification
    /**
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/send-fcm-notification',
        description: 'Send firebase cloud message notification',
        tags: ['Firebase Notification']
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $user = \App\Models\User::find($request->user_id);
        $fcm = $user->fcm_token;
        $project_id = 'doorstep_view';

        if (!$fcm) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }

        $title = $request->title;
        $description = $request->body;
        $doorstepView = config('services.fcm.' . $project_id); # INSERT COPIED PROJECT ID

        $credentialsFilePath = Storage::path('app/json/file.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => $title,
                    "body" => $description,
                ],
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$project_id}/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
}
