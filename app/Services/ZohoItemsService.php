<?php

namespace App\Services;

use App\Http\Requests\Items\FilterRequest;
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
    public function getItems(FilterRequest $request, Organization $organization): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($organization, $request) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/items';
            return $client->get($url, [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'query' => [
                    'search_text' => $request->input('search') ?? null,
                    'filter_by' => $request->input('filter_by') ?? null,
                    'page' => $request->input('page', 1),
                    'per_page' => $request->input('per_page', 20),
                    'sort_column' => $request->input('sort_column', 'name'),
                ],
                'http_errors' => false,
            ]);
        });

        return array_map([$this, 'returnResponse'], $body['items'] ?? []);
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
                    'sku',
                    'product_type'
                ]),
                'http_errors' => false,
            ]);
        });

        return $this->returnResponse($body['item'] ?? []);
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
                    'sku',
                    'product_type'
                ]),
                'http_errors' => false,
            ]);
        });

        return $this->returnResponse($body['item'] ?? []);
    }

    /**
     * @throws Exception
     */
    public function delete(Organization $organization, $itemId): array
    {
        return $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($organization, $itemId) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/items/' . $itemId;
            return $client->delete($url, [
                'headers' => [
                    'content-type' => 'application/json',
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'http_errors' => false,
            ]);
        });
    }

    private function returnResponse(array $body): array
    {
        return array_intersect_key($body, array_flip([
            "item_id",
            "name",
            "item_name",
            "unit",
            "status",
            "source",
            "description",
            "rate",
            "product_type",
            "sku",
            "created_time",
        ]));
    }
}
