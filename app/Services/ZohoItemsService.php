<?php

namespace App\Services;

use App\Http\Requests\Items\ItemsRequest;
use App\Models\Organization;
use App\Traits\AutoRefreshesZohoToken;
use Exception;

class ZohoItemsService
{
    use AutoRefreshesZohoToken;

    /**
     * @throws Exception
     */
    public function getItems(Organization $organization, $request): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($organization, $request) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/items';
            return $client->get($url, [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'query' => [
                    'search_text' => $request['search'] ?? null
                ],
                'http_errors' => false,
            ]);
        });

        return $body['items'] ?? [];
    }

    /**
     * @throws Exception
     */
    public function store(ItemsRequest $request, Organization $organization): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($request, $organization) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/items/';
            return $client->post($url, [
                'headers' => [
                    'content-type' => 'application/json',
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'json' => $request->only([
                    'name',
                    'rate',
                    'description',
                    'tax_id',
                    'sku',
                    'product_type'
                ]),
                'http_errors' => false,
            ]);
        });

        return $body['item'] ?? [];
    }

    /**
     * @throws Exception
     */
    public function update(ItemsRequest $request, Organization $organization, $itemId): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($request, $organization, $itemId) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/items/' . $itemId;
            return $client->put($url, [
                'headers' => [
                    'content-type' => 'application/json',
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'json' => $request->only([
                    'name',
                    'rate',
                    'description',
                    'tax_id',
                    'sku',
                    'product_type'
                ]),
                'http_errors' => false,
            ]);
        });

        return $body['item'] ?? [];
    }
}
