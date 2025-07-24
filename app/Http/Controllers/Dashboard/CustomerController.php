<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\GetCustomerRequest;
use App\Models\Organization;
use App\Services\ZohoCustomerService;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
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

    public function index(GetCustomerRequest $request): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request) {
            $organization = Organization::with('zohoConfig.zohoToken')
                ->findOrFail($request->input('organization_id'));

            return $this->apiResponse('Get data success', $this->zohoCustomerService->getCustomers($organization), Response::HTTP_OK);
        });
    }
}
