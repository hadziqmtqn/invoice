<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Exception;

trait HandlesApiTryCatch
{
    /**
     * Jalankan callback dalam blok try-catch dan otomatis kirim response API.
     *
     * @param callable $callback
     * @param string $errorMessage
     * @return mixed
     */
    public function tryCatchApi(callable $callback, string $errorMessage = 'Internal server error'): mixed
    {
        try {
            return $callback();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->apiResponse($errorMessage, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}