<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::with('user')->latest()->paginate(15);

        return view('admin.businesses.index', compact('businesses'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('businesses')->select('id', 'name', 'mobile')->get();
        return view('admin.businesses.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'           => 'required|exists:users,id|unique:businesses,user_id',
            'name'              => 'required|string|max:100',
            'type'              => 'required|in:farm,shop,transport,store,other',
            'standard_income'   => 'nullable|numeric|min:0',
            'standard_expense'  => 'nullable|numeric|min:0',
            'auto_tag_transactions' => 'required|boolean',
        ]);

        Business::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'standard_income' => $validated['standard_income'] ?? 0,
            'standard_expense' => $validated['standard_expense'] ?? 0,
            'auto_tag_transactions' => $validated['auto_tag_transactions'],
        ]);

        return redirect()->route('admin.businesses.index')->with('success', 'Business created successfully.');
    }

    public function edit($id)
    {
        $business = Business::findOrFail($id);
        return view('admin.businesses.edit', compact('business'));
    }

    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        $validated = $request->validate([
            'name'              => 'required|string|max:100',
            'type'              => 'required|in:farm,shop,transport,store,other',
            'standard_income'   => 'nullable|numeric|min:0',
            'standard_expense'  => 'nullable|numeric|min:0',
            'auto_tag_transactions' => 'required|boolean',
        ]);

        $business->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'standard_income' => $validated['standard_income'] ?? 0,
            'standard_expense' => $validated['standard_expense'] ?? 0,
            'auto_tag_transactions' => $validated['auto_tag_transactions'],
        ]);

        return redirect()->route('admin.businesses.index')->with('success', 'Business updated successfully.');
    }

    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();

        return redirect()->route('admin.businesses.index')->with('success', 'Business deleted successfully.');
    }

    public function toggle($id)
    {
        $business = Business::findOrFail($id);
        $business->update(['active' => ! $business->active]);
        return redirect()->route('admin.businesses.index')->with('success', 'Business status updated.');
    }
}

