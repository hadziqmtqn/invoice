<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    protected static int $code = Response::HTTP_NOT_FOUND;

    public function apiResponse($message = null, $data = null, $code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        return response()->json([
            'success' => $code === Response::HTTP_OK,
            'type' => $code === Response::HTTP_OK ? 'success' : 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function sort($request)
    {
        return $request->input('sort') > 1 ? $request->input('sort') : 20;
    }

    public function paginateResponse($listData, LengthAwarePaginator $paginator, $code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        self::$code = $code;
        return response()->json([
            'status' => self::$code === Response::HTTP_OK ? 'success' : 'error',
            'message' => 'Get data success',
            'data' => [
                'datas' => $listData,
                'currentPage' => $paginator->currentPage(),
                'firstPageUrl' => $paginator->url(1),
                'from' => $paginator->firstItem(),
                'lastPage' => $paginator->lastPage(),
                'lastPageUrl' => $paginator->url($paginator->lastPage()),
                'links' => $paginator->linkCollection(),
                'nextPageUrl' => $paginator->nextPageUrl(),
                'path' => $paginator->path(),
                'perPage' => $paginator->perPage(),
                'prevPageUrl' => $paginator->previousPageUrl(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ]
        ], self::$code);
    }
}
