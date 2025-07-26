<?php

namespace App\Services;

use App\Http\Requests\Organization\FilterRequest;
use App\Models\Organization;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrganizationService
{
    use ApiResponse, HandlesApiTryCatch;

    protected Organization $organization;

    /**
     * @param Organization $organization
     */
    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function getOrganization(FilterRequest $filterRequest): Collection|array
    {
        return $this->organization
            ->filter($filterRequest)
            ->get();
    }

    public function select(FilterRequest $request): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request) {
            $organizations = $this->getOrganization($request);

            return $this->apiResponse('Get data success', $organizations->map(function (Organization $organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name
                ];
            }), Response::HTTP_OK);
        });
    }
}
