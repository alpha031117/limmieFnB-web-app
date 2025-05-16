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
}
