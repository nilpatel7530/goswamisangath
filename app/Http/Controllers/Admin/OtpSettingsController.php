<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtpSettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table('otp_settings')->first();
        if (! $settings) {
            $id = DB::table('otp_settings')->insertGetId([
                'otp_method' => 'sms',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $settings = DB::table('otp_settings')->find($id);
        }
        return view('admin.settings.otp', compact('settings'));
    }

    /**
     * AJAX: Switch OTP method (sms | email)
     */
    public function updateMethod(Request $request)
    {
        $request->validate([
            'otp_method' => 'required|string|in:sms,email',
        ]);

        $settings = DB::table('otp_settings')->first();
        if (! $settings) {
            DB::table('otp_settings')->insert([
                'otp_method' => $request->otp_method,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('otp_settings')->where('id', $settings->id)->update([
                'otp_method' => $request->otp_method,
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP method updated to ' . $request->otp_method . '.',
            'otp_method' => $request->otp_method,
        ]);
    }
}
