<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\Auth\OtpLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Step 1: Mobile number se OTP bhejein
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['mobile' => 'required|digits:10']);

        // $otp = rand(100000, 999999);
        $otp = 1234;
        OtpVerification::updateOrCreate(
            ['mobile' => $request->mobile],
            [
                'otp'        => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
            ]
        );

        // SMS send karein (Service layer mein)
        // app(OtpService::class)->send($request->mobile, $otp);

        return $this->successResponse(
            data: [
                'expires_in' => 600,
                'otp'        => $otp
            ],
            message: 'OTP sent successfully'
        );
    }

    /**
     * Step 2: OTP verify karein aur token return karein
     */
    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|digits:10',
    //         'otp'    => 'required|digits:6',
    //     ]);

    //     $record = OtpVerification::where('mobile', $request->mobile)
    //         ->where('expires_at', '>', now())
    //         ->latest()
    //         ->first();

    //     if (! $record || ! Hash::check($request->otp, $record->otp)) {
    //         return $this->errorResponse('Invalid or expired OTP', code: 401);
    //     }

    //     $record->delete();

    //     $user = User::firstOrCreate(
    //         ['mobile' => $request->mobile],
    //         ['name'   => 'User_' . $request->mobile]
    //     );

    //     $token = $user->createToken('mobile-app')->plainTextToken;

    //     return $this->successResponse([
    //         'token'       => $token,
    //         'user'        => $user,
    //         'is_new_user' => $user->wasRecentlyCreated,
    //     ], 'Login successful');
    // }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp'    => 'required|digits:4',
        ]);

        $record = OtpVerification::where('mobile', $request->mobile)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $record || ! Hash::check($request->otp, $record->otp)) {
            return $this->errorResponse('Invalid or expired OTP', code: 401);
        }

        // OTP matched → delete record
        $record->delete();

        return $this->successResponse(
            data: [],
            message: 'OTP verified successfully'
        );
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(message: 'Logged out successfully');
    }
}
