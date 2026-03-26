<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BillController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/v1/bills
     * Bills list with filters
     * Filters: business_id, year, month, type
     */
    public function index(Request $request)
    {
        $bills = Bill::where('user_id', $request->user()->id)
            ->when($request->business_id, fn($q) => $q->where('business_id', $request->business_id))
            ->when($request->year,        fn($q) => $q->where('year',        $request->year))
            ->when($request->month,       fn($q) => $q->where('month',       $request->month))
            ->when($request->type,        fn($q) => $q->where('bill_type',   $request->type))
            ->with('business')
            ->latest()
            ->paginate($request->per_page ?? 20);

        $data = $bills->map(fn($bill) => [
            'id'          => $bill->id,
            'title'       => $bill->title,
            'bill_type'   => $bill->bill_type,
            'amount'      => (float) $bill->amount,
            'bill_date'   => $bill->bill_date?->format('Y-m-d'),
            'month'       => $bill->month,
            'year'        => $bill->year,
            'file_url'    => $bill->file_path ? Storage::url($bill->file_path) : null,
            'file_type'   => $bill->file_type,
            'business'    => $bill->business ? [
                'id'   => $bill->business->id,
                'name' => $bill->business->name,
            ] : null,
            'notes'       => $bill->notes,
            'created_at'  => $bill->created_at?->format('Y-m-d'),
        ]);

        return $this->successResponse([
            'data' => $data,
            'meta' => [
                'current_page' => $bills->currentPage(),
                'last_page'    => $bills->lastPage(),
                'total'        => $bills->total(),
            ],
        ], 'Bills fetched successfully.');
    }

    /**
     * POST /api/v1/bills
     * Bill upload karo (image ya PDF)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:150',
            'bill_type'   => 'required|in:purchase,sale,expense,income,other',
            'amount'      => 'nullable|numeric|min:0',
            'bill_date'   => 'required|date',
            'business_id' => 'nullable|exists:businesses,id',
            'file'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'notes'       => 'nullable|string|max:500',
        ], [
            'title.required'     => 'Bill ka title required hai.',
            'bill_type.required' => 'Bill type required hai.',
            'bill_date.required' => 'Bill ki date required hai.',
            'file.required'      => 'Bill file (image/PDF) required hai.',
            'file.mimes'         => 'Sirf JPG, PNG, PDF files allowed hain.',
            'file.max'           => 'File size 5MB se zyada nahi honi chahiye.',
        ]);

        // File store karo: storage/app/bills/{user_id}/{year}/{month}/
        $date      = \Carbon\Carbon::parse($validated['bill_date']);
        $folder    = "bills/{$request->user()->id}/{$date->year}/{$date->month}";
        $fileName  = Str::uuid() . '.' . $request->file('file')->getClientOriginalExtension();
        $filePath  = $request->file('file')->storeAs($folder, $fileName, 'local');

        $bill = Bill::create([
            'user_id'     => $request->user()->id,
            'business_id' => $validated['business_id'] ?? null,
            'title'       => $validated['title'],
            'bill_type'   => $validated['bill_type'],
            'amount'      => $validated['amount'] ?? 0,
            'bill_date'   => $validated['bill_date'],
            'month'       => $date->month,
            'year'        => $date->year,
            'file_path'   => $filePath,
            'file_type'   => $request->file('file')->getMimeType(),
            'notes'       => $validated['notes'] ?? null,
        ]);

        return $this->successResponse([
            'id'       => $bill->id,
            'title'    => $bill->title,
            'file_url' => Storage::url($filePath),
        ], 'Bill successfully upload ho gayi.', 201);
    }

    /**
     * GET /api/v1/bills/{id}
     * Single bill detail
     */
    public function show(Request $request, $id)
    {
        $bill = Bill::where('user_id', $request->user()->id)
            ->with('business')
            ->findOrFail($id);

        return $this->successResponse([
            'id'        => $bill->id,
            'title'     => $bill->title,
            'bill_type' => $bill->bill_type,
            'amount'    => (float) $bill->amount,
            'bill_date' => $bill->bill_date?->format('Y-m-d'),
            'file_url'  => $bill->file_path ? Storage::url($bill->file_path) : null,
            'file_type' => $bill->file_type,
            'business'  => $bill->business?->name,
            'notes'     => $bill->notes,
            'month'     => $bill->month,
            'year'      => $bill->year,
        ], 'Bill detail fetched.');
    }

    /**
     * DELETE /api/v1/bills/{id}
     */
    public function destroy(Request $request, $id)
    {
        $bill = Bill::where('user_id', $request->user()->id)->findOrFail($id);

        // File bhi delete karo storage se
        if ($bill->file_path && Storage::exists($bill->file_path)) {
            Storage::delete($bill->file_path);
        }

        $bill->delete();

        return $this->successResponse(null, 'Bill delete ho gayi.');
    }
}
