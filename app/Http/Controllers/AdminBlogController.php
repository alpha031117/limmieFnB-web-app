<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\Blog;

class AdminBlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::paginate(10); // Fix: use paginate()
        return view('admin.index', compact('blogs'));
    }

    public function blog_logs(Request $request)
    {
        // Fetch recipe-related activity logs, paginated
        $logs = Activity::where('log_name', 'blog')
                        ->with(['causer', 'subject']) // eager load user and recipe
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('admin.blog-logs', compact('logs'));
    }

    public function undoLastChange($blogID)
    {
        // Authorize admin only - adjust your auth logic accordingly
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('blog.index')->with('failed', 'Unauthorized Access!');
        }

        $blogs = blog::findOrFail($blogID);

        // Get the last activity (excluding the "created" event) for this recipe
        $lastActivity = Activity::where('subject_type', Blog::class)
            ->where('subject_id', $blogs->id)
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
        $blogs->fill($oldAttributes);
        $blogs->save();

        // Optionally, log this undo action
        activity()
            ->performedOn($blogs)
            ->causedBy(Auth::user())
            ->withProperties(['undo_of_activity_id' => $lastActivity->id])
            ->log('undo');

        return redirect()->back()->with('success', 'Recipe changes undone successfully.');
    }

    public function updateApproval(Request $request, Blog $blog)
    {
        $request->validate([
            'approved' => 'required|boolean',
        ]);

        $blog->is_approved = $request->approved;
        $blog->save();

        $status = $request->approved ? 'approved' : 'disapproved';

        return redirect()->back()->with('success', "Blog post has been {$status}.");
    }

    public function hasInappropriateComment(): bool
    {
        return $this->comments->contains(function ($comment) {
            return $comment ->isInappropriate();
        });
    }
}
