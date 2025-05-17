<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\Recipe;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all recipes, paginated
        $recipes = Recipe::paginate(15);
        return view('admin.index', compact('recipes'));
    }

    public function recipe_logs(Request $request)
    {
        // Fetch recipe-related activity logs, paginated
        $logs = Activity::where('log_name', 'recipe')
                        ->with(['causer', 'subject']) // eager load user and recipe
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('admin.recipe-logs', compact('logs'));
    }

    public function undoLastChange($recipeId)
    {
        // Authorize admin only - adjust your auth logic accordingly
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('recipes.index')->with('failed', 'Unauthorized Access!');
        }

        $recipe = Recipe::findOrFail($recipeId);

        // Get the last activity (excluding the "created" event) for this recipe
        $lastActivity = Activity::where('subject_type', Recipe::class)
            ->where('subject_id', $recipe->id)
            ->where('description', '!=', 'created')
            ->latest()
            ->first();

        if (!$lastActivity) {
            return redirect()->back()->with('error', 'No changes to undo.');
        }

        $oldAttributes = $lastActivity->properties['old'] ?? null;

        if (!$oldAttributes) {
            return redirect()->back()->with('error', 'No previous state to revert to.');
        }

        // Fill the model with old attributes and save
        $recipe->fill($oldAttributes);
        $recipe->save();

        // Optionally, log this undo action
        activity()
            ->performedOn($recipe)
            ->causedBy(Auth::user())
            ->withProperties(['undo_of_activity_id' => $lastActivity->id])
            ->log('undo');

        return redirect()->back()->with('success', 'Recipe changes undone successfully.');
    }
}
