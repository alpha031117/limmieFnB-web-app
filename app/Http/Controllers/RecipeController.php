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
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'ingredients' => 'required',
            'instructions' => 'required',
        ]);
        Recipe::create($validatedData);
        return redirect('/recipes')->with('success', 'Recipe added successfully!');
    }
}
