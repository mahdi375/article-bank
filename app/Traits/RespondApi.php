<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

trait RespondApi
{
    protected function response(
        JsonResource|ResourceCollection|string|array $data,
        $status = Response::HTTP_OK
    ): JsonResponse {
        if (is_string($data)) {
            return response()->json(['message' => $data], $status);
        }

        if (is_array($data)) {
            return response()->json($data, $status);
        }

        return $data->response()->setStatusCode($status);
    }
}
