<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * POST /api/v1/auth/send-otp
     */
    public function sendOtp(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required|digits:10',
            ], [
                'mobile.required' => 'Mobile number is required.',
                'mobile.digits'   => 'Mobile number must be exactly 10 digits.',
            ]);

            // $otp = rand(1000, 9999);
            $otp = 1234;
            OtpVerification::updateOrCreate(
                ['mobile' => $request->mobile],
                [
                    'otp'        => Hash::make($otp),
                    'expires_at' => now()->addMinutes(10),
                ]
            );

            // TODO: Send via SMS gateway
            // SmsService::send($request->mobile, "Your OTP is: {$otp}");

            $data = ['expires_in_seconds' => 600];

            if (config('app.debug')) {
                $data['otp_debug'] = $otp;
            }

            return $this->successResponse($data, 'OTP sent successfully.');
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
     * POST /api/v1/auth/verify-otp
     */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required|digits:10',
                'otp'    => 'required|digits:4',
            ], [
                'mobile.required' => 'Mobile number is required.',
                'mobile.digits'   => 'Mobile number must be exactly 10 digits.',
                'otp.required'    => 'OTP is required.',
                'otp.digits'      => 'OTP must be exactly 4 digits.',
            ]);

            $record = OtpVerification::where('mobile', $request->mobile)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (! $record) {
                return $this->errorResponse(
                    'OTP has expired. Please request a new one.',
                    null,
                    422
                );
            }

            if (! Hash::check($request->otp, $record->otp)) {
                return $this->errorResponse(
                    'Invalid OTP. Please try again.',
                    null,
                    422
                );
            }

            $record->delete();

            $isNewUser = ! User::where('mobile', $request->mobile)->exists();

            $user = User::firstOrCreate(
                ['mobile' => $request->mobile],
                ['name'   => '']
            );

            $user->tokens()->delete();
            $token = $user->createToken('mobile-app')->plainTextToken;

            $message = $isNewUser
                ? 'Account created successfully. Welcome to Vitai Finance!'
                : 'Login successful. Welcome back!';

            return $this->successResponse([
                'token'       => $token,
                'is_new_user' => $isNewUser,
                'user'        => [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'mobile'           => $user->mobile,
                    'gender'           => $user->gender,
                    'user_types'       => $user->user_types ?? [],
                    'reminder_time'    => $user->reminder_time,
                    'profile_complete' => ! empty($user->name) && ! is_null($user->gender),
                ],
            ], $message);
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
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->successResponse(null, 'Logged out successfully.');
        } catch (Throwable $e) {
            return $this->errorResponse(
                'Logout failed. Please try again.',
                config('app.debug') ? $e->getMessage() : null,
                500
            );
        }
    }
}
