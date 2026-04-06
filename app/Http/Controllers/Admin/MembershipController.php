<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership; // We will create this model
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Membership::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                  ->orWhere('name', 'like', "%{$searchTerm}%")
                  ->orWhere('badge', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('features', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by featured
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured == 'yes' ? 1 : 0);
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == 'yes' ? 1 : 0);
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'display_order');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validate sort_by to prevent SQL injection
        $allowedSortColumns = ['id', 'name', 'price', 'visits_allowed', 'interest_limit', 'display_order', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'display_order';
        }
        
        // Validate sort_order
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'desc' : 'asc';
        
        // If sorting by display_order, add secondary sort by price
        if ($sortBy === 'display_order') {
            $query->orderBy('display_order', $sortOrder)->orderBy('price', 'asc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $memberships = $query->get();

        return view('admin.memberships.index', compact('memberships'));
    }

    public function create()
    {
        return view('admin.memberships.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'visits_allowed' => 'required|integer|min:0',
            'interest_limit' => 'required|integer|min:0',
            'features' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'badge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active') ? true : ($request->input('is_active') === '0' ? false : true);

        Membership::create($data);

        return redirect()->route('admin.memberships.index')->with('success', 'Membership plan created successfully.');
    }

    public function edit(Membership $membership)
    {
        return view('admin.memberships.edit', compact('membership'));
    }

    public function update(Request $request, Membership $membership)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'visits_allowed' => 'required|integer|min:0',
            'interest_limit' => 'required|integer|min:0',
            'features' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'badge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active') ? true : ($request->input('is_active') === '0' ? false : true);

        $membership->update($data);

        return redirect()->route('admin.memberships.index')->with('success', 'Membership plan updated successfully.');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();
        return redirect()->route('admin.memberships.index')->with('success', 'Membership plan deleted successfully.');
    }
}