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

            $settings = $user->settings()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'notifications_enabled' => false,
                    'biometric_enabled'     => false,
                ]
            );

            return $this->successResponse([
                'user' => [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'mobile'           => $user->mobile,
                    'gender'           => $user->gender,
                    'reminder_time'    => $user->reminder_time,
                    'profile_complete' => ! empty($user->name) && ! is_null($user->gender),
                    'settings'         => [
                        'notifications_enabled' => $settings->notifications_enabled,
                        'biometric_enabled'     => $settings->biometric_enabled,
                        'pin_set'               => ! is_null($settings->pin_code),
                        'daily_reminder_time'   => $settings->daily_reminder_time,
                        'weekly_budget_limit'   => $settings->weekly_budget_limit,
                        'monthly_budget_limit'  => $settings->monthly_budget_limit,
                    ],
                ],
            ], 'Profile with settings fetched successfully.');
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
                'profile_photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5120',
            ], [
                'name.string'               => 'Name must be a valid string.',
                'name.max'                  => 'Name must not exceed 100 characters.',
                'gender.in'                 => 'Gender must be male, female, or other.',
                'reminder_time.date_format' => 'Reminder time must be in HH:MM format (e.g. 08:30).',
                'profile_photo.image'       => 'Profile photo must be an image file.',
                'profile_photo.mimes'       => 'Profile photo must be jpeg, png, jpg, or gif.',
                'profile_photo.max'         => 'Profile photo must not exceed 5MB.',
            ]);

            $user = $request->user();

            $data = array_filter([
                'name'          => $request->name,
                'gender'        => $request->gender,
                'reminder_time' => $request->reminder_time,
            ], fn($value) => ! is_null($value));

            if ($request->hasFile('profile_photo')) {
                $path         = $request->file('profile_photo')->store('profile_photos', 'public');
                $data['profile_photo'] = $path;
            }

            // ✅ Create user if not exists, otherwise update
            if (! $user->exists) {
                $user->fill($data)->save();
            } else {
                $user->update($data);
            }

            // ✅ Create settings if not exists, otherwise update
            $settings = $user->settings()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'notifications_enabled' => $user->settings?->notifications_enabled ?? false,
                    'biometric_enabled'     => $user->settings?->biometric_enabled ?? false,
                ]
            );

            return $this->successResponse([
                'user' => [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'mobile'           => $user->mobile,
                    'gender'           => $user->gender,
                    'reminder_time'    => $user->reminder_time,
                    'profile_photo'    => $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : null,
                    'profile_complete' => ! empty($user->name) && ! is_null($user->gender),
                    'settings'         => [
                        'notifications_enabled' => $settings->notifications_enabled,
                        'biometric_enabled'     => $settings->biometric_enabled,
                        'pin_set'               => ! is_null($settings->pin_code),
                        'daily_reminder_time'   => $settings->daily_reminder_time,
                        'weekly_budget_limit'   => $settings->weekly_budget_limit,
                        'monthly_budget_limit'  => $settings->monthly_budget_limit,
                    ],
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

    /**
     * POST /api/v1/profile/update-user-types
     */
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
