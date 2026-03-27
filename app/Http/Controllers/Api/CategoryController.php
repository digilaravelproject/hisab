<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/v1/categories
     * User ki saari categories (+ default system categories)
     */
    public function index(Request $request)
    {
        $categories = Category::where(function ($q) use ($request) {
            $q->where('user_id', $request->user()->id)  // user ki categories
                ->orWhereNull('user_id');                  // system default categories
        })
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->orderBy('user_id')   // system pehle, user ki baad
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id'        => $c->id,
                'name'      => $c->name,
                'type'      => $c->type,       // income / expense
                'icon'      => $c->icon,
                'is_custom' => ! is_null($c->user_id),
            ]);

        return $this->successResponse($categories, 'Categories fetched.');
    }
}
