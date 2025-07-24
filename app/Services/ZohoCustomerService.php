<?php

namespace App\Services;

use App\Models\Organization;
use App\Traits\AutoRefreshesZohoToken;
use Exception;

class ZohoCustomerService
{
    use AutoRefreshesZohoToken;

    /**
     * @throws Exception
     */
    public function getCustomers(Organization $organization): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($organization) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/customers?organization_id=' . $organization->organization_id;
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