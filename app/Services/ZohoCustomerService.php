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
    public function getCustomers(Organization $organization, $request): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($organization, $request) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/contacts';
            return $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'query' => [
                    'search_text' => $request['search'] ?? null
                ],
                'http_errors' => false,
            ]);
        });

        return $body['contacts'] ?? [];
    }

    /**
     * @throws Exception
     */
    public function show(Organization $organization, $customerId): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($organization, $customerId) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/contacts/' . $customerId;
            return $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'http_errors' => false,
            ]);
        });

        return $body['contact'] ?? [];
    }
}