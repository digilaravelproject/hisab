<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/v1/profile
     */
    public function show(Request $request)
    {
        try {

            $user = $request->user();

            return $this->successResponse([
                'user' => [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'mobile'           => $user->mobile,
                    'gender'           => $user->gender,
                    'user_types'       => $user->user_types ?? [],
                    'reminder_time'    => $user->reminder_time,
                    'profile_complete' => ! empty($user->name) && ! is_null($user->gender),
                ],
            ], 'Profile fetched successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Something went wrong. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }

    /**
     * POST /api/v1/profile/update
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name'          => 'sometimes|string|max:100',
                'gender'        => 'sometimes|in:male,female,other',
                'reminder_time' => 'sometimes|date_format:H:i',
            ], [
                'name.string'            => 'Name must be a valid string.',
                'name.max'               => 'Name must not exceed 100 characters.',
                'gender.in'              => 'Gender must be male, female, or other.',
                'reminder_time.date_format' => 'Reminder time must be in HH:MM format (e.g. 08:30).',
            ]);

            $user = $request->user();

            $user->update(array_filter([
                'name'          => $request->name,
                'gender'        => $request->gender,
                'reminder_time' => $request->reminder_time,
            ], fn($value) => ! is_null($value)));

            return $this->successResponse([
                'user' => [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'mobile'           => $user->mobile,
                    'gender'           => $user->gender,
                    'user_types'       => $user->user_types ?? [],
                    'reminder_time'    => $user->reminder_time,
                    'profile_complete' => ! empty($user->name) && ! is_null($user->gender),
                ],
            ], 'Profile updated successfully.');
        } catch (ValidationException $e) {
            return $this->errorResponse(
                $e->errors()[array_key_first($e->errors())][0],
                $e->errors(),
                422
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Something went wrong. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }
    public function updateUserTypes(Request $request)
    {
        try {
            $request->validate([
                'user_types'   => 'required|array|min:1',
                'user_types.*' => 'in:employee,farmer,proprietor,business_owner,shopkeeper,transporter',
            ], [
                'user_types.required' => 'Please select at least one role.',
                'user_types.array'    => 'user_types must be an array.',
                'user_types.min'      => 'Please select at least one role.',
                'user_types.*.in'     => 'Invalid role selected. Allowed: employee, farmer, proprietor, business_owner, shopkeeper, transporter.',
            ]);

            $user = $request->user();

            $user->update([
                'user_types' => array_unique($request->user_types),
            ]);

            return $this->successResponse([
                'user' => [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'mobile'           => $user->mobile,
                    'gender'           => $user->gender,
                    'user_types'       => $user->user_types ?? [],
                    'reminder_time'    => $user->reminder_time,
                    'profile_complete' => ! empty($user->name) && ! is_null($user->gender),
                ],
            ], 'User roles updated successfully.');
        } catch (ValidationException $e) {
            return $this->errorResponse(
                $e->errors()[array_key_first($e->errors())][0],
                $e->errors(),
                422
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Something went wrong. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }
}
