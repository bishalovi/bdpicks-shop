<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FacebookConversionService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://graph.facebook.com/v21.0/',
            'timeout' => 5.0,
        ]);
    }

    public function sendEvent($eventName, $customData = [], $eventId = null)
    {
        try {
            $userData = $this->getUserData();
    
            $eventData = [
                'event_name' => $eventName,
                'event_time' => time(),
                'event_source_url' => request()->fullUrl(),
                'action_source' => 'website',
                'user_data' => $userData,
            ];
    
            // Add event_id if provided
            if ($eventId) {
                $eventData['event_id'] = $eventId;
            }
    
            if (!empty($customData)) {
                $eventData['custom_data'] = $customData;
            }
    
            $payload = [
                'data' => [$eventData],
            ];
    
            if (setting('fb_pixel_test_code')) {
                $payload['test_event_code'] = setting('fb_pixel_test_code');
            }
    
            $response = $this->client->post(setting('fb_pixel_id') . '/events', [
                'query' => [
                    'access_token' => setting('fb_access_token'),
                ],
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
    
            Log::info("Facebook CAPI Event Sent", [
                'event_name' => $eventName,
                'event_id' => $eventId ?? 'none',
                'Code' => setting('fb_pixel_test_code'),
            ]);
    
            return json_decode($response->getBody(), true);
    
        } catch (\Exception $e) {
            Log::error('Facebook CAPI Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    private function getUserData()
    {
        $request = request();

        return [
            'client_ip_address' => $request->ip(),
            'client_user_agent' => $request->userAgent(),
            'fbc' => $_COOKIE['_fbc'] ?? null,
            'fbp' => $_COOKIE['_fbp'] ?? null,
            // Add more user data for better matching
            'em' => $this->hashData($request->user()?->email), // Hash email if user logged in
        ];
    }

    // Hash data for privacy
    private function hashData($data)
    {
        if (empty($data)) return null;
        return hash('sha256', trim(strtolower($data)));
    }

    public function sendPurchase($orderData, $eventId = null)
    {
        return $this->sendEvent('Purchase', [
            'currency' => $orderData['currency'] ?? 'BDT',
            'value' => $orderData['value'],
            'content_ids' => $orderData['content_ids'] ?? [],
            'contents' => $orderData['contents'] ?? [],
        ], $eventId);
    }

    public function sendAddToCart($cartData, $eventId = null)
    {
        return $this->sendEvent('AddToCart', [
            'currency' => $cartData['currency'] ?? 'BDT',
            'value' => $cartData['value'],
            'content_ids' => $cartData['content_ids'],
            'contents' => $cartData['contents'] ?? [],
        ], $eventId);
    }

    public function sendSearch($searchData)
    {
        return $this->sendEvent('Search', [
            'search_string' => $searchData['query'],
        ]);
    }

    public function sendViewContent($productData, $eventId = null, $userData = [])
    {
        $eventData = [
            'currency' => $productData['currency'] ?? 'BDT',
            'value' => $productData['value'] ?? 0,
            'content_ids' => [$productData['product_id']],
            'content_name' => $productData['product_name'] ?? '',
            'content_type' => 'product',
            'content_category' => $productData['content_category'] ?? null,
            'event_time' => $productData['event_time'] ?? now()->timestamp,
            'action_source' => $productData['action_source'] ?? 'website',
            'user_data' => $userData // Add hashed identifiers here
        ];
    
        return $this->sendEvent('ViewContent', $eventData, $eventId);
    }
}