<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\MotherTongueMaster;
use App\Models\HighestQualification;
use App\Models\Education;
use App\Models\Occupation;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Hobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string',
            'site_name_type' => 'nullable|string|in:text,image',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'site_logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'site_name_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'site_name_image_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'footer_info' => 'nullable|string',
            'demo_mode' => 'nullable|string|in:on,off',
            'demo_bonus_interests_limit' => 'nullable|integer|min:0',
            'live_at' => 'nullable|date',
            'hide_contact_if_not_accepted' => 'nullable|string|in:on,off',
            'hide_address_if_not_accepted' => 'nullable|string|in:on,off',
            'terms_content' => 'nullable|string',
            'privacy_content' => 'nullable|string',
            'android_app_link' => 'nullable|url',
            'ios_app_link' => 'nullable|url',
        ]);

        if ($request->has('site_name')) {
            SiteSetting::set('site_name', $request->site_name);
        }

        if ($request->has('site_name_type')) {
            SiteSetting::set('site_name_type', $request->site_name_type);
        }

        // Handle Site Logo (Light)
        if ($request->hasFile('site_logo')) {
            $oldLogo = SiteSetting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('site_logo')->store('settings', 'public');
            SiteSetting::set('site_logo', $path);
        }

        // Handle Site Logo (Dark)
        if ($request->hasFile('site_logo_dark')) {
            $oldLogoDark = SiteSetting::get('site_logo_dark');
            if ($oldLogoDark) {
                Storage::disk('public')->delete($oldLogoDark);
            }
            $path = $request->file('site_logo_dark')->store('settings', 'public');
            SiteSetting::set('site_logo_dark', $path);
        }

        // Handle Site Name Image (Light)
        if ($request->hasFile('site_name_image')) {
            $oldImage = SiteSetting::get('site_name_image');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $path = $request->file('site_name_image')->store('settings', 'public');
            SiteSetting::set('site_name_image', $path);
        }

        // Handle Site Name Image (Dark)
        if ($request->hasFile('site_name_image_dark')) {
            $oldImageDark = SiteSetting::get('site_name_image_dark');
            if ($oldImageDark) {
                Storage::disk('public')->delete($oldImageDark);
            }
            $path = $request->file('site_name_image_dark')->store('settings', 'public');
            SiteSetting::set('site_name_image_dark', $path);
        }

        if ($request->has('footer_info')) {
            SiteSetting::set('footer_info', $request->footer_info);
        }

        SiteSetting::set('demo_mode', $request->has('demo_mode') ? 'on' : 'off');

        if ($request->has('demo_bonus_interests_limit')) {
            SiteSetting::set('demo_bonus_interests_limit', $request->demo_bonus_interests_limit);
        }

        if ($request->has('live_at')) {
            SiteSetting::set('live_at', $request->live_at);
        }

        SiteSetting::set('hide_contact_if_not_accepted', $request->has('hide_contact_if_not_accepted') ? 'on' : 'off');
        SiteSetting::set('hide_address_if_not_accepted', $request->has('hide_address_if_not_accepted') ? 'on' : 'off');

        if ($request->has('terms_content')) {
            SiteSetting::set('terms_content', $request->terms_content);
        }

        if ($request->has('privacy_content')) {
            SiteSetting::set('privacy_content', $request->privacy_content);
        }
        
        if ($request->has('android_app_link')) {
            SiteSetting::set('android_app_link', $request->android_app_link);
        }
        
        if ($request->has('ios_app_link')) {
            SiteSetting::set('ios_app_link', $request->ios_app_link);
        }

        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            $oldLogo = SiteSetting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('site_logo')->store('settings', 'public');
            SiteSetting::set('site_logo', $path);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    // ==========================================
    // Language (Mother Tongue) Management
    // ==========================================

    public function language(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatableResponse(MotherTongueMaster::query(), $request, ['id', 'title', 'status'], 'title');
        }
        $languages = MotherTongueMaster::all();
        return view('admin.settings.language', compact('languages'));
    }

    public function storeLanguage(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        MotherTongueMaster::create($request->only('title', 'status'));
        return $this->successResponse('Language added successfully.');
    }

    public function updateLanguage(Request $request, $id)
    {
        $request->validate(['title' => 'required|string|max:255']);
        MotherTongueMaster::findOrFail($id)->update($request->only('title', 'status'));
        return $this->successResponse('Language updated successfully.');
    }

    public function deleteLanguage($id)
    {
        MotherTongueMaster::findOrFail($id)->delete();
        return $this->successResponse('Language deleted successfully.');
    }

    // ==========================================
    // Highest Education Management
    // ==========================================

    public function highestEducation(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatableResponse(HighestQualification::query(), $request, ['id', 'name', 'status', 'is_visible']);
        }
        return view('admin.settings.highest-education');
    }

    public function storeHighestEducation(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        HighestQualification::create($request->only('name', 'status', 'is_visible'));
        return $this->successResponse('Highest Education added successfully.');
    }

    public function updateHighestEducation(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        HighestQualification::findOrFail($id)->update($request->only('name', 'status', 'is_visible'));
        return $this->successResponse('Highest Education updated successfully.');
    }

    public function deleteHighestEducation($id)
    {
        HighestQualification::findOrFail($id)->delete();
        return $this->successResponse('Highest Education deleted successfully.');
    }

    // ==========================================
    // Education Details Management
    // ==========================================

    public function educationDetails(Request $request)
    {
        $highestQualifications = HighestQualification::where('status', 1)->get();

        if ($request->ajax()) {
            $query = Education::with('highestQualification');

            if ($request->filled('highest_qualification_filter')) {
                $query->where('highest_qualification_id', $request->highest_qualification_filter);
            }

            return $this->datatableResponse($query, $request, ['id', 'name', 'status', 'is_visible'], 'name', function ($item) {
                $item->highest_qualification_name = $item->highestQualification->name ?? 'N/A';
                $item->highest_qualification_id = $item->highest_qualification_id;
                return $item;
            });
        }

        return view('admin.settings.education-details', compact('highestQualifications'));
    }

    public function storeEducationDetails(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'highest_education_id' => 'required|exists:highest_qualification_master,id',
        ]);

        Education::create([
            'name' => $request->name,
            'highest_qualification_id' => $request->highest_education_id,
            'status' => $request->status ?? 1,
            'is_visible' => $request->is_visible ?? 1,
        ]);

        return $this->successResponse('Education Details added successfully.');
    }

    public function updateEducationDetails(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $education = Education::findOrFail($id);
        $education->update([
            'name' => $request->name,
            'highest_qualification_id' => $request->highest_education_id ?? $education->highest_qualification_id,
            'status' => $request->status ?? $education->status,
            'is_visible' => $request->is_visible ?? $education->is_visible,
        ]);
        return $this->successResponse('Education Details updated successfully.');
    }

    public function deleteEducationDetails($id)
    {
        Education::findOrFail($id)->delete();
        return $this->successResponse('Education Details deleted successfully.');
    }

    public function getEducationDetailsByQualification($qualificationId)
    {
        $educations = Education::where('highest_qualification_id', $qualificationId)
            ->where('status', 1)
            ->get(['id', 'name']);
        return response()->json(['success' => true, 'data' => $educations]);
    }

    // ==========================================
    // Occupation Management
    // ==========================================

    public function occupation(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatableResponse(Occupation::query(), $request, ['id', 'name', 'status', 'is_visible']);
        }
        $occupations = Occupation::all();
        return view('admin.settings.occupation', compact('occupations'));
    }

    public function storeOccupation(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Occupation::create($request->only('name', 'status', 'is_visible'));
        return $this->successResponse('Occupation added successfully.');
    }

    public function updateOccupation(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Occupation::findOrFail($id)->update($request->only('name', 'status', 'is_visible'));
        return $this->successResponse('Occupation updated successfully.');
    }

    public function deleteOccupation($id)
    {
        Occupation::findOrFail($id)->delete();
        return $this->successResponse('Occupation deleted successfully.');
    }

    // ==========================================
    // Country Management
    // ==========================================

    public function country(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatableResponse(Country::query(), $request, ['id', 'name', 'sortname', 'phone_code', 'status']);
        }
        $countries = Country::all();
        return view('admin.settings.country', compact('countries'));
    }

    public function storeCountry(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Country::create($request->only('name', 'sortname', 'phone_code', 'status'));
        return $this->successResponse('Country added successfully.');
    }

    public function updateCountry(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Country::findOrFail($id)->update($request->only('name', 'sortname', 'phone_code', 'status'));
        return $this->successResponse('Country updated successfully.');
    }

    public function deleteCountry($id)
    {
        Country::findOrFail($id)->delete();
        return $this->successResponse('Country deleted successfully.');
    }

    // ==========================================
    // State Management
    // ==========================================

    public function state(Request $request)
    {
        $countries = Country::where('status', 1)->get();

        if ($request->ajax()) {
            $query = State::with('country');

            if ($request->filled('country_filter')) {
                $query->where('country_id', $request->country_filter);
            }

            return $this->datatableResponse($query, $request, ['id', 'name', 'status', 'is_visible'], 'name', function ($item) {
                $item->country_name = $item->country->name ?? 'N/A';
                return $item;
            });
        }

        return view('admin.settings.state', compact('countries'));
    }

    public function storeState(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:country_manage,id',
        ]);
        State::create($request->only('name', 'country_id', 'status', 'is_visible'));
        return $this->successResponse('State added successfully.');
    }

    public function updateState(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        State::findOrFail($id)->update($request->only('name', 'country_id', 'status', 'is_visible'));
        return $this->successResponse('State updated successfully.');
    }

    public function deleteState($id)
    {
        State::findOrFail($id)->delete();
        return $this->successResponse('State deleted successfully.');
    }

    public function getStatesByCountry($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->where('status', 1)
            ->get(['id', 'name']);
        return response()->json(['success' => true, 'data' => $states]);
    }

    // ==========================================
    // City Management
    // ==========================================

    public function city(Request $request)
    {
        $countries = Country::where('status', 1)->get();

        if ($request->ajax()) {
            $query = City::with('state.country');

            if ($request->filled('country_filter')) {
                $query->whereHas('state', function ($q) use ($request) {
                    $q->where('country_id', $request->country_filter);
                });
            }

            if ($request->filled('state_filter')) {
                $query->where('state_id', $request->state_filter);
            }

            return $this->datatableResponse($query, $request, ['id', 'city_master', 'status', 'is_visible'], 'city_master', function ($item) {
                $item->state_name = $item->state->name ?? 'N/A';
                $item->country_name = $item->state->country->name ?? 'N/A';
                $item->country_id = $item->state->country_id ?? null;
                return $item;
            });
        }

        $states = State::with('country')->get()->map(function ($state) {
            $state->country_name = $state->country->name ?? 'N/A';
            return $state;
        });
        return view('admin.settings.city', compact('countries', 'states'));
    }

    public function storeCity(Request $request)
    {
        $request->validate(['city_master' => 'required|string|max:255']);
        City::create([
            'city_master' => $request->city_master,
            'state_id' => $request->state_id,
            'status' => $request->status ?? 1,
            'is_visible' => $request->is_visible ?? 1,
        ]);
        return $this->successResponse('City added successfully.');
    }

    public function updateCity(Request $request, $id)
    {
        City::findOrFail($id)->update($request->only('city_master', 'state_id', 'status', 'is_visible'));
        return $this->successResponse('City updated successfully.');
    }

    public function deleteCity($id)
    {
        City::findOrFail($id)->delete();
        return $this->successResponse('City deleted successfully.');
    }

    public function getCitiesByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->where('status', 1)
            ->get(['id', 'city_master as name']);
        return response()->json(['success' => true, 'data' => $cities]);
    }

    // ==========================================
    // Hobby Management
    // ==========================================

    public function hobby(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatableResponse(Hobby::query(), $request, ['id', 'name', 'status']);
        }
        return view('admin.settings.hobby');
    }

    public function storeHobby(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:hobbies,name']);
        Hobby::create($request->only('name', 'status'));
        return $this->successResponse('Hobby added successfully.');
    }

    public function updateHobby(Request $request, $id)
    {
        $id = decrypt($id); // Assuming ID might be encrypted or just plain
        $request->validate(['name' => 'required|string|max:255|unique:hobbies,name,' . $id]);
        Hobby::findOrFail($id)->update($request->only('name', 'status'));
        return $this->successResponse('Hobby updated successfully.');
    }

    public function deleteHobby($id)
    {
        $id = decrypt($id);
        Hobby::findOrFail($id)->delete();
        return $this->successResponse('Hobby deleted successfully.');
    }

    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->where('status', 1)->get(['id', 'name']);
        return response()->json($states);
    }

    public function getCities($state_id)
    {
        // Using city_master as name for the dropdown
        $cities = City::where('state_id', $state_id)->where('status', 1)->get(['id', 'city_master as name']);
        return response()->json($cities);
    }

    public function getEducations($qualification_id)
    {
        $educations = Education::where('highest_qualification_id', $qualification_id)->where('status', 1)->get(['id', 'name']);
        return response()->json($educations);
    }

    // ==========================================
    // Helpers
    // ==========================================

    private function successResponse($message)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->back()->with('success', $message);
    }

    private function datatableResponse($query, Request $request, array $columns, $searchColumn = 'name', $transformer = null)
    {
        $totalRecords = $query->count();

        // Search
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where($searchColumn, 'like', "%{$search}%");
        }

        $filteredRecords = $query->count();

        // Order
        $orderColumn = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        }

        // Paginate
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->skip($start)->take($length)->get();

        if ($transformer) {
            $data = $data->map($transformer);
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }
}