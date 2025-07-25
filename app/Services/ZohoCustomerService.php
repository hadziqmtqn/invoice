<?php

namespace App\Services;

use App\Http\Requests\Customer\CustomerRequest;
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
    public function getCustomers(Organization $organization, $request): array
    {
        $body = $this->withZohoToken($organization->zohoConfig, function ($accessToken, $client) use ($organization, $request) {
            $url = $organization->zohoConfig?->zohoToken?->api_domain . '/invoice/v3/contacts';
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

        return $body['contacts'] ?? [];
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
                'json' => [
                    'contact_name' => $request->input('contact_name'),
                    'company_name' => $request->input('company_name'),
                    'website' => $request->input('website'),
                    'notes' => $request->input('notes')
                ],
                'http_errors' => false,
            ]);
        });

        return $body['contact'] ?? [];
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

        return $body['contact'] ?? [];
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
                'json' => [
                    'contact_name' => $request->input('contact_name'),
                    'company_name' => $request->input('company_name'),
                    'website' => $request->input('website'),
                    'notes' => $request->input('notes')
                ],
                'http_errors' => false,
            ]);
        });

        return $body['contact'] ?? [];
    }
}