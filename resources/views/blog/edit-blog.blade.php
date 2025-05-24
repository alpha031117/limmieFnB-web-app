@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-orange-600">Edit Blog</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('blog.update', $Blogs->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Recipe Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Blog Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $Blogs->name) }}" required
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
                <option value="" disabled>Select a category</option>
                @foreach(['dessert', 'lunch', 'dinner', 'vegetarian', 'vegan', 'gluten-free'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $recipe->category) === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            @error('category')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="description" rows="4" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('description', $recipe->description) }}</textarea>
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
            @if ($blog->image_url)
                <p class="mt-2 text-gray-600">Current Image:</p>
                <img src="{{ asset('storage/' . $blog->image_url) }}" alt="{{ $blog->name }}" class="w-40 rounded mt-1">
            @endif
        </div>

        <div>
            <button type="submit" class="bg-orange-600 text-white px-6 py-3 rounded font-semibold hover:bg-orange-700 transition">
                Update Recipe
            </button>
        </div>
    </form>
</div>
@endsection
