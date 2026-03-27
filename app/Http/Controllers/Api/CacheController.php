<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class CacheController extends Controller
{
    use ApiResponseTrait;

    /**
     * POST /api/v1/cache/clear
     * Clear all application cache
     */
    public function clear()
    {
        try {
            Cache::flush();

            return $this->successResponse([
                'status' => 'cleared',
                'message' => 'All cache has been cleared successfully.',
            ], 'Cache cleared successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to clear cache.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }

    /**
     * POST /api/v1/cache/clear-views
     * Clear view cache
     */
    public function clearViews()
    {
        try {
            Artisan::call('view:clear');

            return $this->successResponse([
                'status' => 'cleared',
                'message' => 'View cache has been cleared successfully.',
            ], 'View cache cleared successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to clear view cache.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }

    /**
     * POST /api/v1/cache/clear-routes
     * Clear route cache
     */
    public function clearRoutes()
    {
        try {
            Artisan::call('route:clear');

            return $this->successResponse([
                'status' => 'cleared',
                'message' => 'Route cache has been cleared successfully.',
            ], 'Route cache cleared successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to clear route cache.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }

    /**
     * POST /api/v1/cache/clear-config
     * Clear configuration cache
     */
    public function clearConfig()
    {
        try {
            Artisan::call('config:clear');

            return $this->successResponse([
                'status' => 'cleared',
                'message' => 'Configuration cache has been cleared successfully.',
            ], 'Configuration cache cleared successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to clear configuration cache.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }

    /**
     * POST /api/v1/cache/clear-all
     * Clear ALL caches (views, routes, config, and application cache)
     */
    public function clearAll()
    {
        try {
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Cache::flush();

            return $this->successResponse([
                'status' => 'cleared',
                'message' => 'All caches (views, routes, config, and application cache) have been cleared successfully.',
            ], 'All caches cleared successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to clear all caches.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }
}
