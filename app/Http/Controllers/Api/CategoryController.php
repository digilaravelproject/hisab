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

    /**
     * POST /api/v1/categories
     * Naya custom category banao
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:10',
        ], [
            'name.required' => 'Category ka naam required hai.',
            'type.required' => 'Type required hai.',
            'type.in'       => 'Type sirf income ya expense ho sakta hai.',
        ]);

        // Duplicate check
        $exists = Category::where('user_id', $request->user()->id)
            ->where('name', $validated['name'])
            ->where('type', $validated['type'])
            ->exists();

        if ($exists) {
            return $this->errorResponse('Yeh category pehle se exist karti hai.', null, 422);
        }

        $category = Category::create([
            'user_id' => $request->user()->id,
            'name'    => $validated['name'],
            'type'    => $validated['type'],
            'icon'    => $validated['icon'] ?? null,
        ]);

        return $this->successResponse($category, 'Category create ho gayi.', 201);
    }

    /**
     * PUT /api/v1/categories/{id}
     */
    public function update(Request $request, $id)
    {
        $category = Category::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:60',
            'icon' => 'nullable|string|max:10',
        ]);

        $category->update($validated);

        return $this->successResponse($category->fresh(), 'Category update ho gayi.');
    }

    /**
     * DELETE /api/v1/categories/{id}
     * Sirf user ki custom categories delete ho sakti hain
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $category->delete();

        return $this->successResponse(null, 'Category delete ho gayi.');
    }
}
