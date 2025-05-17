@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-orange-600">Add New Recipe</h2>
    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Recipe Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Recipe Name</label>
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

        <!-- Difficulty -->
        <div>
            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
            <select name="difficulty" id="difficulty" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="" disabled selected>Select difficulty</option>
                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
            </select>
            @error('difficulty')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ingrediants --}}
        <div>
            <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingredients</label>
            <textarea name="ingredients" id="ingredients" rows="4" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('ingredients') }}</textarea>
            @error('ingredients')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Duration -->
        <div>
            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
            <input type="number" name="duration" id="duration" min="1" value="{{ old('duration') }}" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            @error('duration')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Prep Time --}}
        <div>
            <label for="prep_time" class="block text-sm font-medium text-gray-700 mb-1">Preparation Time (minutes)</label>
            <input type="number" name="prep_time" id="prep_time" min="1" value="{{ old('prep_time') }}" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            @error('prep_time')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Cook Time --}}
        <div>
            <label for="cook_time" class="block text-sm font-medium text-gray-700 mb-1">Cooking Time (minutes)</label>
            <input type="number" name="cook_time" id="cook_time" min="1" value="{{ old('cook_time') }}" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            @error('cook_time')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nutrition -->
        <div>
            <label for="nutrition" class="block text-sm font-medium text-gray-700 mb-1">Nutrition</label>
            <input type="text" name="nutrition" id="nutrition" placeholder="e.g. 550 calories, 30g fat, 40g carbs, 15g protein"
                value="{{ old('nutrition') }}" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            @error('nutrition')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Instruction -->
        <div>
            <label for="instruction" class="block text-sm font-medium text-gray-700 mb-1">Instruction</label>
            <textarea name="instruction" id="instruction" rows="4" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('instruction') }}</textarea>
            @error('instruction')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Servings -->
        <div>
            <label for="servings" class="block text-sm font-medium text-gray-700 mb-1">Servings</label>
            <input type="number" name="servings" id="servings" min="1" value="{{ old('servings') }}" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            @error('servings')
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
