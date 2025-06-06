<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Store new comment
    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'blog_id' => 'required|exists:blogs,id',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'content' => $request->comment, // Use 'comment' from form
            'blog_id' => $request->blog_id,
        ]);

        return back()->with('success', 'Comment submitted successfully.');
    }

    // Edit comment form
    public function edit(Comment $comment)
    {
        $this->authorizeUser($comment);

        return view('comments.edit', compact('comment'));
    }

    // Update the comment
    public function update(Request $request, Comment $comment)
    {
    $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    if (auth()->id() !== $comment->user_id) {
        abort(403);
    }

    $comment->content = $request->input('comment');
    $comment->save();

    return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    // Delete the comment
    public function destroy(Comment $comment)
    {
    $comment->delete();

    return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    // Check if the current user is authorized
    private function authorizeUser(Comment $comment)
    {
        $user = Auth::user();

        if (!$user || ($user->id !== $comment->user_id && $user->role !== 'admin')) {
            abort(403, 'Unauthorized action.');
        }
    }
}
