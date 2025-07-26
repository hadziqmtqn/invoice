<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Items\FilterRequest;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;

class ZohoItemsController extends Controller
{
    use ApiResponse, HandlesApiTryCatch;

    public function index(FilterRequest $request): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request) {

        });
    }
}
