@extends('layouts.admin')

@section('title', 'Edit Membership Plan')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Editing: {{ $membership->name }}</h3>
    </div>
    <form action="{{ route('admin.memberships.update', $membership->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Plan Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $membership->name) }}" required>
            </div>
            <div class="form-group">
                <label for="price">Price (in ₹)</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $membership->price) }}" required>
            </div>
            <div class="form-group">
                <label for="visits_allowed">Profile Visits Allowed</label>
                <input type="number" class="form-control" id="visits_allowed" name="visits_allowed" value="{{ old('visits_allowed', $membership->visits_allowed) }}" required>
            </div>
            <div class="form-group">
                <label for="interest_limit">Interests Allowed</label>
                <input type="number" class="form-control" id="interest_limit" name="interest_limit" value="{{ old('interest_limit', $membership->interest_limit) }}" required>
            </div>
            <div class="form-group">
                <label for="features">Features (one per line)</label>
                <textarea class="form-control" id="features" name="features" rows="4">{{ old('features', $membership->features) }}</textarea>
            </div>
            <div class="form-group">
                <label for="badge">Badge Text (e.g., Basic, Standard, Premium)</label>
                <input type="text" class="form-control" id="badge" name="badge" value="{{ old('badge', $membership->badge) }}" placeholder="e.g., Standard">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Short description of the plan">{{ old('description', $membership->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="display_order">Display Order (lower numbers appear first)</label>
                <input type="number" class="form-control" id="display_order" name="display_order" value="{{ old('display_order', $membership->display_order ?? 0) }}" min="0">
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $membership->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">Mark as Featured (MOST POPULAR)</label>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $membership->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active (visible on membership page)</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Plan</button>
            <a href="{{ route('admin.memberships.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

