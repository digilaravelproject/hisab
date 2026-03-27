<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaticPageController extends Controller
{
    public function index()
    {
        $pages = StaticPage::orderBy('sort_order')->get();
        return view('admin.static-pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.static-pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => ['required', 'string', 'max:255', Rule::in(['privacy-policy', 'terms-and-conditions', 'faq'])],
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        StaticPage::create($data);

        return redirect()->route('admin.static-pages.index')->with('success', 'Static page created successfully.');
    }

    public function edit($id)
    {
        $page = StaticPage::findOrFail($id);
        return view('admin.static-pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = StaticPage::findOrFail($id);

        $data = $request->validate([
            'slug' => ['required', 'string', 'max:255', Rule::in(['privacy-policy', 'terms-and-conditions', 'faq'])],
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $page->update($data);

        return redirect()->route('admin.static-pages.index')->with('success', 'Static page updated successfully.');
    }

    public function destroy($id)
    {
        $page = StaticPage::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.static-pages.index')->with('success', 'Static page deleted.');
    }

    public function toggle($id)
    {
        $page = StaticPage::findOrFail($id);
        $page->update(['is_active' => ! $page->is_active]);

        return redirect()->route('admin.static-pages.index')->with('success', 'Static page status changed.');
    }
}
