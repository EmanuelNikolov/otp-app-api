<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'phone' => ['required', 'starts_with:359', 'string', 'size:12'],
        ]);

        $oldOtp = Otp::where('phone', $validatedData['phone'])->where('expires_at', '>', Carbon::now())->first();

        if ($oldOtp) {
            return response()->json(['errors' => ['phone' => 'A new validation code can be sent after 1 minute cooldown']], 422);
        }

        $otp = new Otp;
        $otp->phone = $validatedData['phone'];
        $otp->code = random_int(100000, 999999);
        $otp->sent_at = Carbon::now();
        $otp->expires_at = Carbon::now()->addMinute();
        $otp->save();

        return response()->json(['id' => $otp->id]);
    }
}
