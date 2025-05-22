<?php

namespace App\Http\Controllers;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with('chef')->get();
        return view('recipes.index', compact('recipes'));
    }

    // Display Recipe Details
    public function show($id)
    {
        $recipe = Recipe::with('chef')->findOrFail($id);
        return view('recipes.recipe-detail', compact('recipe'));
    }

    // Display Recipe Form
    public function create()
    {
        return view('recipes.add-recipe');
    }

    // Store New Recipe
    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:dessert,lunch,dinner,vegetarian,vegan,gluten-free',
            'description' => 'required|string',
            'ingredients' =>'required|string',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'duration' => 'required|integer|min:1',
            'prep_time' => 'required|integer|min:1',
            'cook_time' => 'required|integer|min:1',
            'nutrition' => 'required|string|max:255',
            'instruction' => 'required|string',
            'servings' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        } else {
            $imagePath = null; // Or set a default image path if needed
        }
        
        // Find user based on user_id
        $user = \App\Models\User::find($validatedData['user_id']);

        // Create new Recipe
        $recipe = new \App\Models\Recipe();
        $recipe->name = $validatedData['name'];
        $recipe->category = $validatedData['category'];
        $recipe->description = $validatedData['description'];
        $recipe->ingredients = $validatedData['ingredients'];
        $recipe->difficulty = ucfirst($validatedData['difficulty']); // 'Easy', 'Medium', 'Hard'
        $recipe->duration = $validatedData['duration'];
        $recipe->prep_time = $validatedData['prep_time'];
        $recipe->cook_time = $validatedData['cook_time'];
        $recipe->nutrition = $validatedData['nutrition'];
        $recipe->instruction = $validatedData['instruction'];
        $recipe->servings = $validatedData['servings'];
        $recipe->chef_id = $user->id;
        $recipe->image_url = $imagePath ? '/storage/' . $imagePath : null;
        
        // Default rating (optional, you can adjust or add a rating input)
        $recipe->rating = 0;
    
        $recipe->save();
    
        // Redirect with success message
        return redirect()->route('recipes.index')->with('success', 'Recipe added successfully!');
    }
    
    public function myRecipes($user_id)
    {
        // Fetch only recipes created by logged in user, paginate for performance
        $recipes = Recipe::where('chef_id', $user_id)->latest()->paginate(10);

        return view('recipes.my-recipes', compact('recipes'));
    }

    public function edit($recipeID)
    {
        $recipe = Recipe::findOrFail($recipeID);
        // Authorization: only owner or admin can edit
        if (Auth::user()->id !== $recipe->chef_id) {
            \Illuminate\Support\Facades\Log::info('User ID: ' . Auth::user()->id);
            \Illuminate\Support\Facades\Log::info('Recipe User ID: '. $recipe->chef_id);
            return redirect()->route('recipes.index')->with('failed', 'Unauthorized Access!');
        }

        return view('recipes.edit-recipe', compact('recipe'));
    }

    public function update(Request $request, $recipeID)
    {
        $recipe = Recipe::findOrFail($recipeID);

        // Authorization again
        if (Auth::user()->id !== $recipe->chef_id) {
            \Illuminate\Support\Facades\Log::info('User ID: ' . Auth::user()->id);
            \Illuminate\Support\Facades\Log::info('Recipe User ID: '. $recipe->chef_id);
            return redirect()->route('recipes.index')->with('failed', 'Unauthorized Access!');
        }

        // Validation rules (match your recipe fields)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'ingredients' =>'required|string',
            'duration' => 'required|integer|min:1',
            'prep_time' => 'required|integer|min:0',
            'cook_time' => 'required|integer|min:0',
            'nutrition' => 'required|string',
            'instruction' => 'required|string',
            'servings' => 'required|integer|min:1',
            'image_url' => 'nullable|image|max:2048',
        ]);

        // Update data fields
        $recipe->fill($validated);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');

            // Delete old image if exists
            if ($recipe->image_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($recipe->image_url);
            }

            $recipe->image_url = $path;
        }

        $recipe->save();

        return redirect()->route('recipes.index', $recipe)->with('success', 'Recipe updated successfully.');
    }

    public function destroy(Request $request, $recipeID)
    {
        \Illuminate\Support\Facades\Log::info('Recipe ID: ' . $recipeID);
        // Authorize: only owner or admin can delete
        $user = Auth::user();
        $recipe = Recipe::findOrFail($recipeID);

        if (!$user || ($user->id !== $recipe->chef_id)) {
            return redirect()->route('recipes.index')->with('failed', 'Unauthorized Access!');
        }

        // Delete image file from storage if exists
        if ($recipe->image_url && \Illuminate\Support\Facades\Storage::disk('public')->exists('recipes/' . basename($recipe->image_url))) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('recipes/' . basename($recipe->image_url));
        }

        // Delete recipe record
        $recipe->delete();

        // Redirect back with success message
        return redirect()->route('recipes.my', $user->id)
                        ->with('success', 'Recipe deleted successfully.');
    }

}
