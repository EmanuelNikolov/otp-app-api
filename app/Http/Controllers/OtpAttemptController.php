<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\OtpAttempt;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtpAttemptController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'phone' => ['required', 'starts_with:359', 'string', 'size:12'],
            'code'   => ['required', 'string', 'size:6'],
        ]);

        $otp = Otp::where('phone', $validatedData['phone'])->orderBy('created_at', 'desc')->first();

        if (!$otp) {
            return response()->json(['errors' => ['phone' => 'No OTP was created for this phone']], 422);
        }

        $oldOtpAttempts = OtpAttempt::where('otp_id', $otp->id)
            ->whereBetween('created_at', [Carbon::now()->subMinute(), Carbon::now()]);

        if ($oldOtpAttempts->count() > 2) {
            return response()->json(['errors' => ['code' => 'Max 3 attempts in 1 minute']], 422);
        }

        $otpAttempt = new OtpAttempt;
        $otpAttempt->otp_id = $otp->id;
        $otpAttempt->code = $validatedData['code'];
        $otpAttempt->save();

        if ($validatedData['code'] === $otp->code) {
            return response()->json(['success' => 'Welcome to SMSBump!']);
        }

        return response()->json(['id' => $otpAttempt->id]);
    }
}
