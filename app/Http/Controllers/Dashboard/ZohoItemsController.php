<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Items\FilterRequest;
use App\Http\Requests\Items\ItemsRequest;
use App\Models\Organization;
use App\Services\ZohoItemsService;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ZohoItemsController extends Controller
{
    use ApiResponse, HandlesApiTryCatch;

    protected ZohoItemsService $zohoItemsService;

    /**
     * @param ZohoItemsService $zohoItemsService
     */
    public function __construct(ZohoItemsService $zohoItemsService)
    {
        $this->zohoItemsService = $zohoItemsService;
    }

    public function index(FilterRequest $request, Organization $organization): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request, $organization) {
            $organization->load('zohoConfig.zohoToken');

            return $this->apiResponse('Get data successfully', [
                'organizationSlug' => $organization->slug,
                'organizationName' => $organization->name,
                'organizationId' => $organization->organization_id,
                'items' => $this->zohoItemsService->getItems($request, $organization),
            ], Response::HTTP_OK);
        });
    }

    public function store(ItemsRequest $request, Organization $organization): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request, $organization) {
            $organization->load('zohoConfig.zohoToken');

            return $this->apiResponse('Item created successfully', [
                'organizationSlug' => $organization->slug,
                'organizationName' => $organization->name,
                'organizationId' => $organization->organization_id,
                'item' => $this->zohoItemsService->store($request, $organization),
            ], Response::HTTP_OK);
        });
    }

    public function update(ItemsRequest $request, Organization $organization, string $itemId): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request, $organization, $itemId) {
            $organization->load('zohoConfig.zohoToken');

            return $this->apiResponse('Item updated successfully', [
                'organizationSlug' => $organization->slug,
                'organizationName' => $organization->name,
                'organizationId' => $organization->organization_id,
                'item' => $this->zohoItemsService->update($request, $organization, $itemId),
            ], Response::HTTP_OK);
        });
    }

    public function destroy(Organization $organization, string $itemId): JsonResponse
    {
        return $this->tryCatchApi(function () use ($organization, $itemId) {
            $organization->load('zohoConfig.zohoToken');

            return $this->apiResponse('Item updated successfully', [
                'organizationSlug' => $organization->slug,
                'organizationName' => $organization->name,
                'organizationId' => $organization->organization_id,
                'item' => $this->zohoItemsService->delete($organization, $itemId),
            ], Response::HTTP_OK);
        });
    }
}
