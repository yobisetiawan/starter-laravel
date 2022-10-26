<?php

namespace App\Services;

class FCMService
{

    protected $apiKey;
    protected $baseUrl;
    protected $client;

    public function __construct()
    {
        $this->apiKey = env("FCM_SERVER_KEY");
        $this->baseUrl = env("FCM_SERVER_URL");
        $this->client = new \GuzzleHttp\Client();
    }


    public function sendPushNotification($token, $notification)
    {

        try {
            $body = [
                "to" => $token,
                "notification" => [
                    "body" => $notification['body'] ?? null,
                    "content_available" => true,
                    "priority" => "high",
                    "subtitle" => $notification['subtitle'] ?? null,
                    "title" => $notification['title'] ?? null,
                    "tag" => $notification['tag'] ?? 'notification'
                ]
            ];

            $ress = $this->client->request('POST', $this->baseUrl . '/fcm/send', [
                'body' =>  json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' =>  "key=$this->apiKey",
                ],
            ]);

            return json_decode($ress->getBody(), true);
        } catch (\Throwable $th) {
            //do nothing
        }
    }
}
