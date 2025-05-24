@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-orange-600">Add New Blog</h2>
    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Blog Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Blog Title</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category" id="category" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 hover:bg-gray-50">
                <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select a category</option>
                <option value="dessert" {{ old('category') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                <option value="lunch" {{ old('category') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                <option value="dinner" {{ old('category') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                <option value="vegetarian" {{ old('category') == 'vegetarian' ? 'selected' : '' }}>Vegetarian</option>
                <option value="vegan" {{ old('category') == 'vegan' ? 'selected' : '' }}>Vegan</option>
                <option value="gluten-free" {{ old('category') == 'gluten-free' ? 'selected' : '' }}>Gluten-Free</option>
            </select>
            @error('category')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="description" rows="4" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Image Upload -->
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <!-- Submit Button -->
        <div>
            <button type="submit" class="bg-orange-600 text-white px-6 py-3 rounded font-semibold hover:bg-orange-700 transition">
                Add Recipe
            </button>
        </div>
    </form>
</div>
@endsection
