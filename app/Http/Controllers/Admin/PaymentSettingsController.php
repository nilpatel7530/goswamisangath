<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentSettingsController extends Controller
{
    /**
     * Display payment settings
     */
    public function index()
    {
        $settings = DB::table('payment_settings')->first();
        return view('admin.settings.payment', compact('settings'));
    }

    /**
     * Store or update payment settings
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key_id' => 'required|string|max:255',
            'key_secret' => 'required|string|max:255',
            'mode' => 'required|in:test,live',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existing = DB::table('payment_settings')->first();

        $data = [
            'key_id' => $request->key_id,
            'key_secret' => $request->key_secret,
            'mode' => $request->mode,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('payment_settings')
                ->where('id', $existing->id)
                ->update($data);
            $message = 'Payment settings updated successfully!';
        } else {
            $data['created_at'] = now();
            DB::table('payment_settings')->insert($data);
            $message = 'Payment settings saved successfully!';
        }

        return redirect()->back()->with('success', $message);
    }
}
