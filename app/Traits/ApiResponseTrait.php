<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse(
        string $message = 'Error',
        mixed $errors = null,
        int $code = 400
    ): JsonResponse {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    protected function paginatedResponse(
        mixed $data,
        string $message = 'Success'
    ): JsonResponse {
        // $data may be a paginator, or a resource response object from ->response()->getData()
        if (is_object($data) && method_exists($data, 'items')) {
            $items = $data->items();
            $meta = [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ];
        } elseif (is_object($data) && property_exists($data, 'data')) {
            $items = $data->data;
            $meta = property_exists($data, 'meta') ? (array)$data->meta : [];
        } else {
            // fallback to a normal success payload if structure is not paginated
            return $this->successResponse($data, $message);
        }

        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $items,
            'meta'    => $meta,
        ]);
    }
}
