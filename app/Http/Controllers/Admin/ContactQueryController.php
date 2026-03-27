<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactQuery;
use Illuminate\Http\Request;

class ContactQueryController extends Controller
{
    public function index()
    {
        $queries = ContactQuery::orderBy('created_at', 'desc')->get();
        return view('admin.contact-queries.index', compact('queries'));
    }

    public function show($id)
    {
        $query = ContactQuery::findOrFail($id);
        return view('admin.contact-queries.show', compact('query'));
    }

    public function updateStatus(Request $request, $id)
    {
        $query = ContactQuery::findOrFail($id);
        $data = $request->validate([
            'is_resolved' => 'required|boolean',
        ]);

        $query->update([
            'is_resolved' => $data['is_resolved'],
            'resolved_at' => $data['is_resolved'] ? now() : null,
        ]);

        return redirect()->route('admin.contact-queries.index')->with('success', 'Query status updated.');
    }

    public function destroy($id)
    {
        $query = ContactQuery::findOrFail($id);
        $query->delete();

        return redirect()->route('admin.contact-queries.index')->with('success', 'Query deleted.');
    }
}
