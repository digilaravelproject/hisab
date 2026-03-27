<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class BankAccountController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/v1/bank-accounts
     * User ke saare bank accounts
     */
    public function index(Request $request)
    {
        try {
            $accounts = BankAccount::where('user_id', $request->user()->id)
                ->latest()
                ->get()
                ->map(fn($account) => [
                    'id'                   => $account->id,
                    'bank_name'            => $account->bank_name,
                    'account_holder_name'  => $account->account_holder_name,
                    'account_number'       => $account->account_number,
                    'ifsc_code'            => $account->ifsc_code,
                    'account_type'         => $account->account_type,
                    'business_type'        => $account->business_type,
                    'is_primary'           => $account->is_primary,
                    'auto_tag'             => $account->auto_tag,
                    'created_at'           => $account->created_at?->format('Y-m-d'),
                ]);

            return $this->successResponse(
                $accounts,
                'Bank accounts fetched successfully.'
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to fetch bank accounts. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }

    /**
     * POST /api/v1/bank-accounts
     * Naya bank account add karo
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'bank_name'           => 'required|string|max:100',
                'account_holder_name' => 'required|string|max:100',
                'account_number'      => 'required|string|max:30',
                'ifsc_code'           => 'nullable|string|max:15',
                'account_type'        => 'required|string|max:100',
                'business_type'       => 'nullable|string|max:100',
                'is_primary'          => 'nullable|boolean',
                'auto_tag'            => 'nullable|boolean',
            ], [
                'bank_name.required'           => 'Bank name is required.',
                'account_holder_name.required' => 'Account holder name is required.',
                'account_number.required'      => 'Account number is required.',
                'account_type.required'        => 'Account type is required.',
            ]);

            // Agar is_primary true hai to pehle sab ko false karo
            if ($request->boolean('is_primary')) {
                BankAccount::where('user_id', $request->user()->id)
                    ->update(['is_primary' => false]);
            }

            // Pehla account hamesha primary hoga
            $isPrimary = $request->boolean('is_primary');
            if (! BankAccount::where('user_id', $request->user()->id)->exists()) {
                $isPrimary = true;
            }

            $account = BankAccount::create([
                'user_id'              => $request->user()->id,
                'bank_name'            => $request->bank_name,
                'account_holder_name'  => $request->account_holder_name,
                'account_number'       => $request->account_number,
                'ifsc_code'            => $request->ifsc_code,
                'account_type'         => $request->account_type,
                'business_type'        => $request->business_type,
                'is_primary'           => $isPrimary,
                'auto_tag'             => $request->boolean('auto_tag'),
            ]);

            return $this->successResponse([
                'id'                   => $account->id,
                'bank_name'            => $account->bank_name,
                'account_holder_name'  => $account->account_holder_name,
                'account_number'       => $account->account_number,
                'ifsc_code'            => $account->ifsc_code,
                'account_type'         => $account->account_type,
                'business_type'        => $account->business_type,
                'is_primary'           => $account->is_primary,
                'auto_tag'             => $account->auto_tag,
            ], 'Bank account added successfully.', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse(
                $e->errors()[array_key_first($e->errors())][0],
                $e->errors(),
                422
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to add bank account. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }
    /**
     * PUT /api/v1/bank-accounts/{id}
     * Bank account update karo
     */
    public function update(Request $request, $id)
    {
        try {
            $account = BankAccount::where('user_id', $request->user()->id)
                ->findOrFail($id);

            $request->validate([
                'bank_name'           => 'sometimes|string|max:100',
                'account_holder_name' => 'sometimes|string|max:100',
                'account_number'      => 'sometimes|string|max:30',
                'ifsc_code'           => 'nullable|string|max:15',
                'account_type'        => 'sometimes|string|max:100',
                'business_type'       => 'nullable|string|max:100',
                'is_primary'          => 'nullable|boolean',
                'auto_tag'            => 'nullable|boolean',
            ], [
                'bank_name.max'           => 'Bank name must not exceed 100 characters.',
                'account_number.max'      => 'Account number must not exceed 30 characters.',
                'ifsc_code.max'           => 'IFSC code must not exceed 15 characters.',
            ]);

            // Agar is_primary true kiya to baaki sab false karo
            if ($request->boolean('is_primary')) {
                BankAccount::where('user_id', $request->user()->id)
                    ->where('id', '!=', $id)
                    ->update(['is_primary' => false]);
            }

            $account->update([
                'bank_name'            => $request->bank_name            ?? $account->bank_name,
                'account_holder_name'  => $request->account_holder_name  ?? $account->account_holder_name,
                'account_number'       => $request->account_number        ?? $account->account_number,
                'ifsc_code'            => $request->has('ifsc_code')      ? $request->ifsc_code    : $account->ifsc_code,
                'account_type'         => $request->account_type          ?? $account->account_type,
                'business_type'        => $request->has('business_type')  ? $request->business_type : $account->business_type,
                'is_primary'           => $request->has('is_primary')     ? $request->boolean('is_primary') : $account->is_primary,
                'auto_tag'             => $request->has('auto_tag')       ? $request->boolean('auto_tag')   : $account->auto_tag,
            ]);

            return $this->successResponse([
                'id'                   => $account->id,
                'bank_name'            => $account->bank_name,
                'account_holder_name'  => $account->account_holder_name,
                'account_number'       => $account->account_number,
                'ifsc_code'            => $account->ifsc_code,
                'account_type'         => $account->account_type,
                'business_type'        => $account->business_type,
                'is_primary'           => $account->is_primary,
                'auto_tag'             => $account->auto_tag,
            ], 'Bank account updated successfully.');
        } catch (ValidationException $e) {
            return $this->errorResponse(
                $e->errors()[array_key_first($e->errors())][0],
                $e->errors(),
                422
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to update bank account. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }

    /**
     * DELETE /api/v1/bank-accounts/{id}
     */
    public function destroy(Request $request, $id)
    {
        try {
            $account = BankAccount::where('user_id', $request->user()->id)
                ->findOrFail($id);

            $account->delete();

            return $this->successResponse(null, 'Bank account removed successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Failed to remove bank account. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }
}
