<?php

namespace App\Http\Controllers;
use App\Models\Recipe;

use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::all();
        return view('recipes.index', compact('recipes'));
    }

    // Display Recipe Details
    public function show($id)
    {
        $recipe = Recipe::find($id);
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
        $recipe->chef_name = $user->name;
        $recipe->image_url = $imagePath ? '/storage/' . $imagePath : null;
        
        // Default rating (optional, you can adjust or add a rating input)
        $recipe->rating = 0;
    
        $recipe->save();
    
        // Redirect with success message
        return redirect()->route('recipes.index')->with('success', 'Recipe added successfully!');
    }
    
    
}
