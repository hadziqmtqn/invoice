<?php

namespace App\Http\Controllers\Dashboard\BaseData;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppController extends Controller
{
    use ApiResponse, HandlesApiTryCatch;

    public function index(): JsonResponse
    {
        return $this->tryCatchApi(function () {
            return $this->apiResponse('Get data success', [
                'appName' => config('app.name'),
                'appLogo' => asset('assets/favicon.png')
            ], Response::HTTP_OK);
        });
    }
}
