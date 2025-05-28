<?php

namespace App\Http\Controllers;

use App\Models\Review;
// use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Store new review
    public function store(Request $request)
    {
        $userId = auth()->id();


        // Check if review already exists for this user and recipe
        // $existingReview = $Blog->reviews()->where('user_id', $userId)->first();

        // if ($existingReview) {
        //     return back()->with('error', 'You have already submitted a review for this blog.');
        // }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'recipe_id' => 'required|exists:recipes,id', // Assuming recipe_id is the foreign key in reviews table
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'recipe_id' => $request->recipe_id, // Assuming $review is the recipe model
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }

    // Show form to edit review
    public function edit(Review $review)
    {
        $this->authorizeUser($review);

        return view('reviews.edit', compact('review'));
    }

    // Update the review
    public function update(Request $request, Review $review)
    {
        $this->authorizeUser($review);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('recipes.show', $review->blogID)->with('success', 'Review updated successfully.');
    }

    // Delete the review
    public function destroy(Review $review)
    {
        $this->authorizeUser($review);

        $review->delete();

        return redirect()->route('blog.show', $review->blogID)->with('success', 'Review deleted successfully.');
    }

    // Authorization helper: only review author or admin can edit/delete
    private function authorizeUser(Review $review)
    {
        $user = Auth::user();

        if (!$user || ($user->id !== $review->user_id && $user->role !== 'admin')) {
            abort(403, 'Unauthorized action.');
        }
    }
}
