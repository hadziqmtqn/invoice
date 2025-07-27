<?php

namespace App\Services;

use App\Models\ZohoConfig;
use App\Models\ZohoToken;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;

class ZohoTokenService
{
    /**
     * @throws Exception
     */
    public static function requestAndStoreToken($config): true
    {
        if (!($config instanceof ZohoConfig)) {
            throw new Exception('Parameter harus instance dari ZohoConfig');
        }

        $client = new Client();

        $accessTokenExists = $config->zohoToken?->access_token ? 'refresh_token' : 'authorization_code';

        $data = [
            'client_id' => $config->client_id,
            'client_secret' => $config->client_secret,
            'redirect_uri' => $config->redirect_uri,
            'grant_type' => $accessTokenExists,
        ];

        if ($accessTokenExists === 'authorization_code') {
            $data['code'] = $config->code;
        }

        if ($accessTokenExists === 'refresh_token') {
            $data['refresh_token'] = $config->refresh_token;
        }

        try {
            $response = $client->post('https://accounts.zoho.com/oauth/v2/token', [
                'form_params' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200 && isset($body['access_token'])) {
                ZohoToken::updateOrCreate(
                    ['zoho_config_id' => $config->id],
                    [
                        'access_token' => $body['access_token'],
                        'refresh_token' => $body['refresh_token'] ?? $config->refresh_token,
                        'api_domain' => $body['api_domain'],
                        'token_type' => $body['token_type'],
                        'expires_in' => $body['expires_in'] ?? 3600,
                        'expired_at' => Carbon::now()->addSeconds($body['expires_in'] ?? 3600),
                    ]
                );

                ZohoConfig::find($config->id)
                    ->update([
                        'refresh_token' => $body['refresh_token'] ?? $config->refresh_token
                    ]);
                return true;
            } else {
                throw new Exception('Failed to get token: ' . json_encode($body));
            }
        } catch (GuzzleException $e) {
            throw new Exception('Request error: ' . $e->getMessage());
        }
    }
}