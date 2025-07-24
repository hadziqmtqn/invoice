<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZohoConfig\ZohoConfigRequest;
use App\Models\ZohoConfig;
use App\Services\ZohoTokenService;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ZohoConfigController extends Controller
{
    use ApiResponse, HandlesApiTryCatch;

    public function index(): JsonResponse
    {
        return $this->tryCatchApi(function () {
            $zohoConfig = ZohoConfig::with('organization:id,name,organization_id')
                ->firstOrFail();

            return $this->apiResponse('Get data success', [
                'id' => $zohoConfig->id,
                'organizationName' => $zohoConfig->organization?->name,
                'organizationId' => $zohoConfig->organization?->organization_id,
                'code' => $zohoConfig->code,
                'clientId' => $zohoConfig->client_id,
                'clientSecret' => $zohoConfig->client_secret,
                'redirectUrl' => $zohoConfig->redirect_url,
                'refreshToken' => $zohoConfig->refresh_token
            ], Response::HTTP_OK);
        });
    }

    public function update(ZohoConfigRequest $request, ZohoConfig $zohoConfig): JsonResponse
    {
        $zohoConfig->load('zohoToken');

        return $this->tryCatchApi(function () use ($request, $zohoConfig) {

            DB::beginTransaction();
            $zohoConfig->code = $request->input('code');
            $zohoConfig->client_id = $request->input('client_id');
            $zohoConfig->client_secret = $request->input('client_secret');
            $zohoConfig->redirect_url = $request->input('redirect_url');
            $zohoConfig->refresh_token = $request->input('refresh_token');
            $zohoConfig->save();

            ZohoTokenService::requestAndStoreToken($zohoConfig);
            DB::commit();

            return $this->apiResponse('Get data success', [
                'id' => $zohoConfig->id,
                'code' => $zohoConfig->code,
                'clientId' => $zohoConfig->client_id,
                'clientSecret' => $zohoConfig->client_secret,
                'redirectUrl' => $zohoConfig->redirect_url,
                'refreshToken' => $zohoConfig->refresh_token
            ], Response::HTTP_OK);
        });
    }
}
