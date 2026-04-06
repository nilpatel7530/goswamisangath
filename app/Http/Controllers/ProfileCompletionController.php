<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProfileCompletionController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // If profile is already complete (for now we check if residential_address is set)
        // In a real app, you might have a dedicated is_profile_complete flag
        if ($user->residential_address) {
            return redirect()->route('dashboard');
        }

        $countries = DB::table('country_manage')->where('status', 1)->get();

        return view('profile.complete-profile', compact('countries'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'residential_address' => 'required|string',
            'residential_country_id' => 'required|exists:country_manage,id',
            'residential_state_id' => 'required|exists:state_master,id',
            'residential_city_id' => 'required|exists:city_master,id',
            'working_address' => 'nullable|string',
            'working_country_id' => 'nullable|exists:country_manage,id',
            'working_state_id' => 'nullable|exists:state_master,id',
            'working_city_id' => 'nullable|exists:city_master,id',
            'is_handicapped' => 'required|boolean',
            'marital_status' => 'required|string|in:Unmarried,Widowed,Divorced,Separated',
            'height' => 'required|string',
            'weight' => 'required|numeric|min:30|max:200',
            'additional_info' => 'nullable|string',
            'id_proof' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $data = $request->only([
            'residential_address',
            'working_address',
            'marital_status',
            'height',
            'weight',
            'additional_info'
        ]);

        // Map IDs to names for string columns
        if ($request->residential_country_id) {
            $country = DB::table('country_manage')->where('id', $request->residential_country_id)->first();
            $data['residential_country'] = $country->name;
            $data['country_id'] = $country->id;
            $data['country'] = $country->name; // Also update main country field
        }
        if ($request->residential_state_id) {
            $state = DB::table('state_master')->where('id', $request->residential_state_id)->first();
            $data['residential_state'] = $state->name;
            $data['state_id'] = $state->id;
            $data['state'] = $state->name; // Also update main state field
        }
        if ($request->residential_city_id) {
            $city = DB::table('city_master')->where('id', $request->residential_city_id)->first();
            $data['residential_city'] = $city->name;
            $data['city_id'] = $city->id;
            $data['city'] = $city->name; // Also update main city field
        }

        if ($request->working_country_id) {
            $country = DB::table('country_manage')->where('id', $request->working_country_id)->first();
            $data['working_country'] = $country->name;
        }
        if ($request->working_state_id) {
            $state = DB::table('state_master')->where('id', $request->working_state_id)->first();
            $data['working_state'] = $state->name;
        }
        if ($request->working_city_id) {
            $city = DB::table('city_master')->where('id', $request->working_city_id)->first();
            $data['working_city'] = $city->name;
        }

        // Handle Same as Residential Logic
        if ($request->has('same_as_residential')) {
            $data['working_country'] = $data['residential_country'] ?? null;
            $data['working_state'] = $data['residential_state'] ?? null;
            $data['working_city'] = $data['residential_city'] ?? null;
            $data['working_address'] = $data['residential_address'] ?? null;
        }
        
        $data['physically_handicap'] = $request->is_handicapped ? 'Yes' : 'No';

        // Handle ID Proof upload
        if ($request->hasFile('id_proof')) {
            $imageName = time() . '_id_' . $user->id . '.' . $request->id_proof->extension();
            $request->id_proof->move(storage_path('app/public/id_proofs'), $imageName);
            $data['id_proof'] = 'id_proofs/' . $imageName;
            $data['verification_status'] = 'pending';
        }

        $user->update($data);

        return redirect()->route('dashboard')->with('success', 'Profile completed successfully! Welcome to your dashboard.');
    }
}
