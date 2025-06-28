<?php

namespace App\Services;

use App\Models\ZohoConfig;
use App\Traits\AutoRefreshesZohoToken;
use Exception;

class ZohoCustomerService
{
    use AutoRefreshesZohoToken;

    /**
     * @throws Exception
     */
    public function getCustomers(ZohoConfig $config): array
    {
        $config->load(['zohoToken', 'organization']);

        $body = $this->withZohoToken($config, function ($accessToken, $client) use ($config) {
            $url = "{$config->zohoToken?->api_domain}/invoice/v3/customers?organization_id={$config->organization?->organization_id}";
            return $client->get($url, [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
            ]);
        });

        return $body['contacts'] ?? [];
    }
}