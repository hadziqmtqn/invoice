<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZohoConfig\ZohoConfigRequest;
use App\Models\Organization;
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

    public function index(Organization $organization): JsonResponse
    {
        $organization->load('zohoConfig.zohoToken');

        return $this->tryCatchApi(function () use ($organization) {
            return $this->apiResponse('Get data success', [
                'id' => $organization->zohoConfig?->id,
                'organizationName' => $organization->name,
                'organizationId' => $organization->organization_id,
                'code' => $organization->zohoConfig?->code,
                'clientId' => $organization->zohoConfig?->client_id,
                'clientSecret' => $organization->zohoConfig?->client_secret,
                'redirectUrl' => $organization->zohoConfig?->redirect_url,
                'refreshToken' => $organization->zohoConfig?->refresh_token
            ], Response::HTTP_OK);
        });
    }

    public function update(ZohoConfigRequest $request, Organization $organization): JsonResponse
    {
        $organization->load('zohoConfig.zohoToken');

        return $this->tryCatchApi(function () use ($request, $organization) {
            DB::beginTransaction();
            $zohoConfig = ZohoConfig::organizationId($organization->id)
                ->lockForUpdate()
                ->firstOrNew();
            $zohoConfig->organization_id = $organization->id;
            $zohoConfig->code = $request->input('code');
            $zohoConfig->client_id = $request->input('client_id');
            $zohoConfig->client_secret = $request->input('client_secret');
            $zohoConfig->redirect_url = $request->input('redirect_url');
            $zohoConfig->refresh_token = $request->input('refresh_token');
            $zohoConfig->save();

            if ($zohoConfig->code != $request->input('code') || $zohoConfig->client_id != $request->input('client_id') || $zohoConfig->client_secret != $request->input('client_secret')) {
                ZohoTokenService::requestAndStoreToken($zohoConfig);
            }
            DB::commit();

            return $this->apiResponse('Get data success', [
                'organizationName' => $organization->name,
                'code' => $zohoConfig->code,
                'clientId' => $zohoConfig->client_id,
                'clientSecret' => $zohoConfig->client_secret,
                'redirectUrl' => $zohoConfig->redirect_url,
                'refreshToken' => $zohoConfig->refresh_token
            ], Response::HTTP_OK);
        });
    }
}
