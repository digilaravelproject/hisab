<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class SettingsController extends Controller
{
    use ApiResponseTrait;

    public function show(Request $request)
    {
        try {
            $user = $request->user();

            $settings = $user->settings()->firstOrCreate([
                'user_id' => $user->id,
            ], [
                'notifications_enabled' => false,
                'biometric_enabled'     => false,
            ]);

            return $this->successResponse([
                'settings' => [
                    'notifications_enabled' => $settings->notifications_enabled,
                    'biometric_enabled'     => $settings->biometric_enabled,
                    'pin_set'               => ! is_null($settings->pin_code),
                    'daily_reminder_time'   => $settings->daily_reminder_time,
                    'weekly_budget_limit'   => $settings->weekly_budget_limit,
                    'monthly_budget_limit'  => $settings->monthly_budget_limit,
                ],
            ], 'Settings fetched successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Something went wrong. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }

    public function toggleNotifications(Request $request)
    {
        try {
            $request->validate([
                'notifications_enabled' => 'required|boolean',
            ]);

            $user = $request->user();
            $settings = $user->settingsOrCreate();
            $settings->update(['notifications_enabled' => $request->boolean('notifications_enabled')]);

            return $this->successResponse([
                'settings' => [
                    'notifications_enabled' => $settings->notifications_enabled,
                ],
            ], 'Notification setting updated successfully.');
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

    public function setPin(Request $request)
    {
        try {
            $request->validate([
                'pin' => ['required', 'digits:4', 'numeric'],
            ], [
                'pin.required' => 'PIN is required.',
                'pin.digits'   => 'PIN must be exactly 4 digits.',
                'pin.numeric'  => 'PIN must be numeric.',
            ]);

            $user = $request->user();
            $settings = $user->settingsOrCreate();
            $settings->update(['pin_code' => bcrypt($request->pin)]);

            return $this->successResponse([
                'settings' => [
                    'pin_set' => true,
                ],
            ], 'PIN set successfully.');
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

    public function toggleBiometric(Request $request)
    {
        try {
            $request->validate([
                'biometric_enabled' => 'required|boolean',
            ]);

            $user = $request->user();
            $settings = $user->settingsOrCreate();
            $settings->update(['biometric_enabled' => $request->boolean('biometric_enabled')]);

            return $this->successResponse([
                'settings' => [
                    'biometric_enabled' => $settings->biometric_enabled,
                ],
            ], 'Biometric setting updated successfully.');
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

    public function setDailyReminder(Request $request)
    {
        try {
            $request->validate([
                'daily_reminder_time' => 'required|date_format:H:i',
            ], [
                'daily_reminder_time.required' => 'Daily reminder time is required.',
                'daily_reminder_time.date_format' => 'Daily reminder time must be in HH:MM format (e.g. 08:30).',
            ]);

            $user = $request->user();
            $settings = $user->settingsOrCreate();
            $settings->update(['daily_reminder_time' => $request->daily_reminder_time]);

            return $this->successResponse([
                'settings' => [
                    'daily_reminder_time' => $settings->daily_reminder_time,
                ],
            ], 'Daily reminder time updated successfully.');
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

    public function setWeeklyBudgetLimit(Request $request)
    {
        try {
            $request->validate([
                'weekly_budget_limit' => 'required|numeric|min:0|max:999999999.99',
            ], [
                'weekly_budget_limit.required' => 'Weekly budget limit is required.',
                'weekly_budget_limit.numeric' => 'Weekly budget limit must be a number.',
                'weekly_budget_limit.min' => 'Weekly budget limit must be at least 0.',
                'weekly_budget_limit.max' => 'Weekly budget limit is too large.',
            ]);

            $user = $request->user();
            $settings = $user->settingsOrCreate();
            $settings->update(['weekly_budget_limit' => $request->weekly_budget_limit]);

            return $this->successResponse([
                'settings' => [
                    'weekly_budget_limit' => $settings->weekly_budget_limit,
                ],
            ], 'Weekly budget limit updated successfully.');
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

    public function setMonthlyBudgetLimit(Request $request)
    {
        try {
            $request->validate([
                'monthly_budget_limit' => 'required|numeric|min:0|max:999999999.99',
            ], [
                'monthly_budget_limit.required' => 'Monthly budget limit is required.',
                'monthly_budget_limit.numeric' => 'Monthly budget limit must be a number.',
                'monthly_budget_limit.min' => 'Monthly budget limit must be at least 0.',
                'monthly_budget_limit.max' => 'Monthly budget limit is too large.',
            ]);

            $user = $request->user();
            $settings = $user->settingsOrCreate();
            $settings->update(['monthly_budget_limit' => $request->monthly_budget_limit]);

            return $this->successResponse([
                'settings' => [
                    'monthly_budget_limit' => $settings->monthly_budget_limit,
                ],
            ], 'Monthly budget limit updated successfully.');
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
