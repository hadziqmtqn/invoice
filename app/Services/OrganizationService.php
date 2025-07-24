<?php

namespace App\Services;

use App\Models\Organization;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Illuminate\Http\Request;
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

    public function select(Request $request): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request) {
            $organizations = $this->organization
                ->filter($request)
                ->get();

            return $this->apiResponse('Get data success', $organizations->map(function (Organization $organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name
                ];
            }), Response::HTTP_OK);
        });
    }
}
