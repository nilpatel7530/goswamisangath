<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Refresh user to get latest data from database
        $user->refresh();
        
        // Get all master data for dropdowns
        $motherTongues = DB::table('mothertongue_master')->where('status', 1)->get();
        $highestQualifications = DB::table('highest_qualification_master')->where('status', 1)->where('is_visible', 1)->get();
        $educations = DB::table('education_master')->where('status', 1)->where('is_visible', 1)->get();
        $occupations = DB::table('occupation_master')->where('status', 1)->where('is_visible', 1)->get();
        $countries = DB::table('country_manage')->where('status', 1)->get();
        $states = DB::table('state_master')->where('is_visible', 1)->get();
        $cities = DB::table('city_master')->where('is_visible', 1)->get();
        $raashis = DB::table('raashi_master')->where('status', 1)->orderBy('name')->get();
        $hobbies = DB::table('hobbies')->where('status', 1)->orderBy('name')->get();
        $userHobbies = $user->hobbies()->pluck('hobbies.id')->toArray();

        
        // Calculate profile completeness
        $completeness = $this->calculateProfileCompleteness($user);
        
        // Parse DOB if exists
        $birthDay = null;
        $birthMonth = null;
        $birthYear = null;
        if ($user->dob) {
            $dob = Carbon::parse($user->dob);
            $birthDay = $dob->day;
            $birthMonth = $dob->month;
            $birthYear = $dob->year;
        }
        
        return view('pages.edit-profile', compact(
            'user', 
            'motherTongues', 
            'highestQualifications', 
            'educations', 
            'occupations', 
            'countries', 
            'states', 
            'cities',
            'raashis',
            'completeness',
            'birthDay',
            'birthMonth',
            'birthYear',
            'hobbies',
            'userHobbies'
        ));
    }

    /**
     * Show the ID verification page.
     */
    public function verifyId()
    {
        $user = Auth::user();
        return view('pages.verify-id', compact('user'));
    }

    public function storeVerifyId(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_proof' => 'required|image|mimes:jpeg,png,jpg,pdf|max:4096',
        ]);

        if ($request->hasFile('id_proof')) {
            // Delete old file if exists
            if ($user->id_proof && File::exists(storage_path('app/public/' . $user->id_proof))) {
                File::delete(storage_path('app/public/' . $user->id_proof));
            }

            $imageName = time() . '_id_' . $user->id . '.' . $request->id_proof->extension();
            $request->id_proof->move(storage_path('app/public/id_proofs'), $imageName);
            
            $user->update([
                'id_proof' => 'id_proofs/' . $imageName,
                'verification_status' => 'pending',
            ]);

            return back()->with('success', 'ID Proof uploaded successfully! Admin will review it soon.');
        }

        return back()->with('error', 'Failed to upload ID proof. Please try again.');
    }
    
    /**
     * Calculate profile completeness percentage
     */
    private function calculateProfileCompleteness($user)
    {
        $fields = [
            'full_name', 'email', 'mobile_number', 'gender', 'dob', 'height', 'weight',
            'marital_status', 'mother_tongue', 'raashi',
            'highest_education', 'education_details', 'occupation', 'employed_in',
            'annual_income', 'country', 'state', 'city', 'profile_image',
            'residential_address', 'residential_country', 'residential_state', 'residential_city',
            'working_address', 'working_country', 'working_state', 'working_city',
            'physically_handicap', 'additional_info'
        ];
        
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $filled++;
            }
        }
        
        return round(($filled / count($fields)) * 100);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validate the incoming data
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'mobile_number' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'profile_image' => ['nullable', 'string'], // base64 string from cropper
            'gender' => ['nullable', 'string', 'in:male,female'],
            'birth_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'birth_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'birth_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'height' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'integer'],
            'marital_status' => ['nullable', 'string', 'max:255'],
            'mother_tongue' => ['nullable', 'string', 'max:255'],
            'raashi' => ['nullable', 'string', 'max:255'],
            'highest_education_id' => ['nullable', 'integer', 'exists:highest_qualification_master,id'],
            'education_id' => ['nullable', 'integer', 'exists:education_master,id'],
            'occupation_id' => ['nullable', 'integer', 'exists:occupation_master,id'],
            'employed_in' => ['nullable', 'string', 'in:Business,Defence,Government,Not Employed,Private,Others'],
            'annual_income' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'diet' => ['nullable', 'string', 'max:255'],
            'languages_known' => ['nullable', 'string'],
            'residential_address' => ['nullable', 'string'],
            'residential_country' => ['nullable', 'string', 'max:255'],
            'residential_state' => ['nullable', 'string', 'max:255'],
            'residential_city' => ['nullable', 'string', 'max:255'],
            'working_address' => ['nullable', 'string'],
            'working_country' => ['nullable', 'string', 'max:255'],
            'working_state' => ['nullable', 'string', 'max:255'],
            'working_city' => ['nullable', 'string', 'max:255'],
            'is_handicapped' => ['nullable', 'boolean'],
            'additional_info' => ['nullable', 'string'],
            'hobbies' => ['nullable', 'array'],
            'hobbies.*' => ['string', 'max:255'],
        ]);

        // 2. Get existing columns
        $existingColumns = [];
        try {
            $columns = DB::select("SHOW COLUMNS FROM users");
            foreach ($columns as $column) {
                $existingColumns[] = $column->Field;
            }
        } catch (\Exception $e) {
            $existingColumns = (new \App\Models\User())->getFillable();
        }

        // 3. Prepare the data for updating
        $updateData = $request->except([
            '_token', 
            '_method', 
            'profile_image', 
            'additional_photos',
            'birth_day', 
            'birth_month', 
            'birth_year', 
            'highest_education_id',
            'education_id',
            'occupation_id',
            'is_handicapped',
            'hobbies'
        ]);
        
        if ($request->has('is_handicapped')) {
            $updateData['physically_handicap'] = $request->is_handicapped ? 'Yes' : 'No';
        }
        
        // Map IDs to names (database stores strings, not IDs)
        if ($request->highest_education_id && in_array('highest_education', $existingColumns)) {
            $qualification = DB::table('highest_qualification_master')->where('id', $request->highest_education_id)->first();
            if ($qualification) {
                $updateData['highest_education'] = $qualification->name;
            }
        }
        
        if ($request->education_id && in_array('education_details', $existingColumns)) {
            $education = DB::table('education_master')->where('id', $request->education_id)->first();
            if ($education) {
                $updateData['education_details'] = $education->name;
            }
        }
        
        if ($request->occupation_id && in_array('occupation', $existingColumns)) {
            $occupation = DB::table('occupation_master')->where('id', $request->occupation_id)->first();
            if ($occupation) {
                $updateData['occupation'] = $occupation->name;
            }
        }

        // 4. Handle profile image (base64 from cropper)
        if ($request->profile_image && in_array('profile_image', $existingColumns)) {
            $imageData = $request->profile_image;
            
            // Handle different base64 formats
            if (strpos($imageData, 'data:image/') === 0) {
                // Extract image type and data
                preg_match('/data:image\/(\w+);base64,(.+)/', $imageData, $matches);
                $imageType = $matches[1] ?? 'jpeg';
                $image = $matches[2] ?? str_replace('data:image/jpeg;base64,', '', $imageData);
            } else {
                $image = str_replace('data:image/jpeg;base64,', '', $imageData);
                $imageType = 'jpeg';
            }
            
            $image = str_replace(' ', '+', $image);
            $decodedImage = base64_decode($image);
            
            if ($decodedImage !== false) {
                // Ensure profiles directory exists
                $profilesDir = storage_path('app/public/profiles');
                if (!\Illuminate\Support\Facades\File::exists($profilesDir)) {
                    \Illuminate\Support\Facades\File::makeDirectory($profilesDir, 0755, true);
                }
                
                // Delete old profile image if exists
                if ($user->profile_image && \Illuminate\Support\Facades\File::exists(storage_path('app/public/' . $user->profile_image))) {
                    \Illuminate\Support\Facades\File::delete(storage_path('app/public/' . $user->profile_image));
                }
                
                // Generate unique filename
                $imageName = 'profile_' . $user->id . '_' . time() . '.' . $imageType;
                $imagePath = storage_path('app/public/profiles/' . $imageName);
                
                // Save the image
                \Illuminate\Support\Facades\File::put($imagePath, $decodedImage);
                
                // Add watermark
                \App\Services\WatermarkService::addWatermark($imagePath);
                
                // Store relative path in database
                $updateData['profile_image'] = 'profiles/' . $imageName;
            }
        }
        
        // 5. Handle additional photos
        if ($request->filled('additional_photos')) {
            try {
                $photosData = json_decode($request->additional_photos, true);
                if (is_array($photosData) && !empty($photosData)) {
                    $profilesDir = storage_path('app/public/profiles');
                    if (!\Illuminate\Support\Facades\File::exists($profilesDir)) {
                        \Illuminate\Support\Facades\File::makeDirectory($profilesDir, 0755, true);
                    }
                    
                    foreach ($photosData as $photo) {
                        if (isset($photo['data'])) {
                            $imageData = $photo['data'];
                            
                            // Extract image type and data
                            if (strpos($imageData, 'data:image/') === 0) {
                                preg_match('/data:image\/(\w+);base64,(.+)/', $imageData, $matches);
                                $imageType = $matches[1] ?? 'jpeg';
                                $image = $matches[2] ?? '';
                            } else {
                                $image = str_replace('data:image/jpeg;base64,', '', $imageData);
                                $imageType = 'jpeg';
                            }
                            
                            $image = str_replace(' ', '+', $image);
                            $decodedImage = base64_decode($image);
                            
                            if ($decodedImage !== false) {
                                // Generate unique filename
                                $imageName = 'photo_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $imageType;
                                $imagePath = storage_path('app/public/profiles/' . $imageName);
                                
                                // Save the image
                                \Illuminate\Support\Facades\File::put($imagePath, $decodedImage);
                                
                                // Add watermark
                                \App\Services\WatermarkService::addWatermark($imagePath);
                                
                                // Store in user_photos table if it exists, otherwise just log
                                try {
                                    if (Schema::hasTable('user_photos')) {
                                        DB::table('user_photos')->insert([
                                            'user_id' => $user->id,
                                            'photo_path' => 'profiles/' . $imageName,
                                            'is_profile' => 0,
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                    }
                                } catch (\Exception $e) {
                                    // Table might not exist, continue anyway
                                    \Log::info('Could not save additional photo: ' . $e->getMessage());
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error processing additional photos: ' . $e->getMessage());
            }
        }
        
        // 6. Combine date fields
        if ($request->birth_day && $request->birth_month && $request->birth_year && in_array('dob', $existingColumns)) {
            $updateData['dob'] = Carbon::createFromDate($request->birth_year, $request->birth_month, $request->birth_day)->format('Y-m-d');
        }

        // 7. Filter to only include existing columns
        $filteredData = [];
        foreach ($updateData as $key => $value) {
            if (in_array($key, $existingColumns)) {
                $filteredData[$key] = $value;
            }
        }

        // 8. Update the user's record
        $user->update($filteredData);
        
        // Refresh user to get updated data
        $user->refresh();

        // 9. Log user activity
        try {
            DB::table('user_activities')->insert([
                'user_id' => $user->id,
                'activity' => 'Updated profile information.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Activity table might not exist
        }

        // 10. Handle Hobbies
        if ($request->has('hobbies')) {
            $hobbyIds = [];
            foreach ($request->hobbies as $hobbyName) {
                if (is_numeric($hobbyName)) {
                    $hobbyIds[] = $hobbyName;
                } else {
                    $hobby = \App\Models\Hobby::firstOrCreate(
                        ['name' => $hobbyName],
                        ['status' => 1]
                    );
                    $hobbyIds[] = $hobby->id;
                }
            }
            $user->hobbies()->sync($hobbyIds);
        } else {
            $user->hobbies()->detach();
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Get states for a given country.
     */
    public function getStates(Request $request)
    {
        $states = DB::table('state_master')
            ->where('country_id', $request->country_id)
            ->where('is_visible', 1)
            ->get();
        return response()->json($states);
    }

    /**
     * Get cities for a given state.
     */
    public function getCities(Request $request)
    {
        $cities = DB::table('city_master')
            ->where('state_id', $request->state_id)
            ->where('is_visible', 1)
            ->orderBy('city_master', 'ASC')
            ->get();
        return response()->json($cities);
    }
}
