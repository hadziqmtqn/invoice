<?php

namespace App\Services;

use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Requests\Customer\FilterRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Organization;
use App\Traits\AutoRefreshesZohoToken;
use Exception;

class ZohoCustomerService
{
    use AutoRefreshesZohoToken;

    /**
     * @throws Exception
     */
    public function getCustomers(FilterRequest $request, Organization $organization): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($organization, $request) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/contacts';
            return $client->get($url, [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'query' => [
                    'search_text' => $request->input('search') ?? null,
                    'filter_by' => $request->input('filter_by') ?? null,
                    'page' => $request->input('page') ?? 1,
                    'per_page' => $request->input('per_page') ?? 10,
                    'sort_column' => $request->input('sort_column') ?? 'contact_name',
                    'sort_order' => $request->input('sort_order') ?? 'A',
                ],
                'http_errors' => false,
            ]);
        });

        return [
            'data' => array_map([$this, 'returnResponse'], $body['contacts'] ?? []),
            'meta' => $body['page_context'] ?? [
                'page' => 1,
                'per_page' => 20,
                'has_more_page' => false,
                'total_pages' => 1,
                'total_count' => 0
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function store(CustomerRequest $request, Organization $organization): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($request, $organization) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/contacts/';
            return $client->post($url, [
                'headers' => [
                    'content-type' => 'application/json',
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'json' => $request->only([
                    'contact_name',
                    'company_name',
                    'website',
                    'notes'
                ]),
                'http_errors' => false,
            ]);
        });

        return $this->returnResponse($body['contact'] ?? []);
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
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'http_errors' => false,
            ]);
        });

        return $this->returnResponse($body['contact'] ?? []);
    }

    /**
     * @throws Exception
     */
    public function update(UpdateCustomerRequest $request, Organization $organization, $customerId): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($request, $organization, $customerId) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/contacts/' . $customerId;
            return $client->put($url, [
                'headers' => [
                    'content-type' => 'application/json',
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'X-com-zoho-invoice-organizationid' => $organization->organization_id
                ],
                'json' => $request->only([
                    'contact_name',
                    'company_name',
                    'website',
                    'notes'
                ]),
                'http_errors' => false,
            ]);
        });

        return $this->returnResponse($body['contact'] ?? []);
    }

    // return response
    private function returnResponse(array $body): array
    {
        return array_intersect_key($body, array_flip([
            'contact_id',
            'contact_name',
            'company_name',
            'contact_number',
            'contact_tax_information',
            'first_name',
            'last_name',
            'designation',
            'department',
            'website',
            'notes',
            'email',
            'mobile',
            'status'
        ]));
    }
}