<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\FilterRequest;
use App\Http\Requests\Organization\OrganizationRequest;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrganizationController extends Controller
{
    use ApiResponse, HandlesApiTryCatch;

    protected OrganizationService $organizationService;

    /**
     * @param OrganizationService $organizationService
     */
    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function index(FilterRequest $request): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request) {
            $organizations = $this->organizationService->getOrganization($request);

            return $this->apiResponse('Get data success', $organizations->map(function (Organization $organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'organizationId' => $organization->organization_id
                ];
            }), Response::HTTP_OK);
        });
    }

    public function store(OrganizationRequest $request): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request) {
            $organization = Organization::lockForUpdate()
                ->firstOrNew();

            $organization->name = $request->input('name');
            $organization->organization_id = $request->input('organization_id');
            $organization->save();

            return $this->apiResponse('Create data success', $organization, Response::HTTP_OK);
        });
    }

    public function update(OrganizationRequest $request, Organization $organization): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request, $organization) {
            $organization->name = $request->input('name');
            $organization->organization_id = $request->input('organization_id');
            $organization->save();

            return $this->apiResponse('Update data success', $organization, Response::HTTP_OK);
        });
    }

    public function select(FilterRequest $request)
    {
        return $this->organizationService->select($request);
    }
}
