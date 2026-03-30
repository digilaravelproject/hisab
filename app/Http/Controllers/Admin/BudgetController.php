<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with(['user', 'category'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.budgets.index', compact('budgets'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.budgets.create', compact('users', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'target_amount' => 'required|numeric|min:0',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
        ]);

        $duplicate = Budget::where('user_id', $data['user_id'])
            ->where('category_id', $data['category_id'])
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->exists();

        if ($duplicate) {
            return back()->withInput()->withErrors([
                'category_id' => 'A budget already exists for this user, category and month.',
            ]);
        }

        Budget::create($data);

        return redirect()->route('admin.budgets.index')->with('success', 'Budget created successfully.');
    }

    public function edit($id)
    {
        $budget = Budget::findOrFail($id);
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.budgets.edit', compact('budget', 'users', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $budget = Budget::findOrFail($id);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'target_amount' => 'required|numeric|min:0',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
        ]);

        $duplicate = Budget::where('user_id', $data['user_id'])
            ->where('category_id', $data['category_id'])
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->where('id', '!=', $budget->id)
            ->exists();

        if ($duplicate) {
            return back()->withInput()->withErrors([
                'category_id' => 'A budget already exists for this user, category and month.',
            ]);
        }

        $budget->update($data);

        return redirect()->route('admin.budgets.index')->with('success', 'Budget updated successfully.');
    }

    public function destroy($id)
    {
        $budget = Budget::findOrFail($id);
        $budget->delete();

        return redirect()->route('admin.budgets.index')->with('success', 'Budget deleted successfully.');
    }
}
