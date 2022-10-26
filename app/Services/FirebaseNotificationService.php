<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Firebase\JWT\JWT;

class FirebaseNotificationService
{
    protected $privateKey;
    protected $tokenUri;
    protected $clientEmail;
    protected $baseUrl;
    protected $projectId;
    protected $guzzle;

    public function __construct()
    {
        $this->privateKey =  env("FCM_PRIVATE_KEY");
        $this->tokenUri = env("FCM_tokenUri");
        $this->clientEmail = env("FCM_clientEmail");
        $this->projectId = env("FCM_PROJECT_ID");
        $this->baseUrl = env("FCM_FCM_V1_URL");
        $this->guzzle = new \GuzzleHttp\Client();
    }

    public function getOauthToken()
    {
        try {
            $now_seconds = time();

            $privateKey = $this->privateKey;

            $payload = [
                'iss' => $this->clientEmail,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => $this->tokenUri,
                'exp' => $now_seconds + (60),
                'iat' => $now_seconds
            ];

            $jwt = JWT::encode($payload, $privateKey, 'RS256');

            $post = [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ];

            $response = $this->guzzle->request('POST', "https://oauth2.googleapis.com/token", [
                'body' =>  json_encode($post),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
            ]);

            $jsonObj = json_decode($response->getBody(), true);

            return $jsonObj['access_token'];
        } catch (\Exception $ex) {
            // do nothing;
        }
    }

    public function sendNotif($body, $access_token)
    {
        try {
            $apiurl = $this->baseUrl . $this->projectId . '/messages:send';

            $result = $this->guzzle->request('POST', $apiurl, [
                'body' =>  json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],
            ]);

            $result = json_decode($result->getBody(), true);

            return $result;
        } catch (\Exception $ex) {
            // do nothing;
        }
    }
}
