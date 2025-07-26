<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\FilterRequest;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Organization;
use App\Services\ZohoCustomerService;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ZohoCustomersController extends Controller
{
    use ApiResponse, HandlesApiTryCatch;

    protected ZohoCustomerService $zohoCustomerService;

    /**
     * @param ZohoCustomerService $zohoCustomerService
     */
    public function __construct(ZohoCustomerService $zohoCustomerService)
    {
        $this->zohoCustomerService = $zohoCustomerService;
    }

    public function index(FilterRequest $request): JsonResponse
    {
        $organization = Organization::with('zohoConfig.zohoToken')
            ->findOrFail($request->input('organization_id'));

        return $this->tryCatchApi(function () use ($organization, $request) {
            return $this->apiResponse('Get data success', [
                'organizationSlug' => $organization->slug,
                'organizationName' => $organization->name,
                'organizationId' => $organization->organization_id,
                'custumers' => $this->zohoCustomerService->getCustomers($organization, $request)
            ], Response::HTTP_OK);
        });
    }

    public function store(CustomerRequest $request): JsonResponse
    {
        $organization = Organization::with('zohoConfig.zohoToken')
            ->findOrFail($request->input('organization_id'));

        return $this->tryCatchApi(function () use ($request, $organization) {
            return $this->apiResponse('Create data success', $this->zohoCustomerService->store($request, $organization), Response::HTTP_OK);
        });
    }

    public function show(Organization $organization, $customerId): JsonResponse
    {
        $organization->load('zohoConfig.zohoToken');

        return $this->tryCatchApi(function () use ($organization, $customerId) {
            return $this->apiResponse('Get data success', $this->zohoCustomerService->show($organization, $customerId), Response::HTTP_OK);
        });
    }

    public function update(UpdateCustomerRequest $request, Organization $organization, $customerId): JsonResponse
    {
        $organization->load('zohoConfig.zohoToken');

        return $this->tryCatchApi(function () use ($request, $organization, $customerId) {
            return $this->apiResponse('Update data success', $this->zohoCustomerService->update($request, $organization, $customerId), Response::HTTP_OK);
        });
    }
}
