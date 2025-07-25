<?php

namespace App\Traits;

use App\Models\ZohoConfig;
use App\Models\ZohoToken;
use App\Services\ZohoTokenService;
use Exception;
use GuzzleHttp\Client;

trait AutoRefreshesZohoToken
{
    /**
     * Helper untuk request ke Zoho API yang otomatis refresh token jika expired.
     *
     * @param ZohoConfig $config
     * @param callable $callback menerima ($accessToken, $client)
     * @return mixed
     * @throws Exception
     */
    public function withZohoToken(ZohoConfig $config, callable $callback): mixed
    {
        $token = ZohoToken::where('zoho_config_id', $config->id)->first();
        if (!$token) {
            throw new Exception('Token tidak valid');
        }

        $token->refresh(); // ambil data terbaru jika ada update didatabase
        $accessToken = $token->access_token;

        $client = new Client();

        // Eksekusi callback pertama kali
        $response = $callback($accessToken, $client);

        if (is_array($response)) {
            return $response;
        }

        $body = json_decode($response->getBody()->getContents(), true);
        $status = $response->getStatusCode();

        // Deteksi token expired (401, 400, code 57, atau message mengandung 'expired')
        $tokenExpired = false;
        if ($status === 401 || $status === 400) {
            if (isset($body['code']) && (int)$body['code'] === 57) {
                $tokenExpired = true;
            }
            if (isset($body['message']) && str_contains($body['message'], 'expired')) {
                $tokenExpired = true;
            }
        }

        // Jika expired, refresh token dan ulangi request sekali lagi
        if ($tokenExpired) {
            ZohoTokenService::requestAndStoreToken($config);
            // Ambil access token terbaru
            $token->refresh();
            $accessToken = $token->access_token;

            $response = $callback($accessToken, $client);

            if (is_array($response)) {
                return $response;
            }

            $body = json_decode($response->getBody()->getContents(), true);
            $status = $response->getStatusCode();
            if ($status < 200 || $status >= 300) {
                throw new Exception('Gagal fetch data setelah refresh token: ' . json_encode($body));
            }
        } elseif ($status < 200 || $status >= 300) {
            throw new Exception('Gagal fetch data: ' . json_encode($body));
        }

        return $body;
    }
}