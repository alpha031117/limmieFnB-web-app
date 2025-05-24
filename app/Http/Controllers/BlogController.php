<?php

namespace App\Http\Controllers;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BlogController extends Controller
{
public function index()
{
    $blogs = Blog::with('author')->get(); // Use 'author' not 'user'
    return view('blog.index', compact('blogs'));
}


    // Display Recipe Details
    public function show($id)
    {
        $blog = Blog::with(['author', 'reviews.user'])->findOrFail($id);
        return view('blog.blog-detail', compact('blog'));
    }

    // Display Recipe Form
    public function create()
    {
        return view('blog.add-blog');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog', 'public');
        } else {
            $imagePath = null; // Or set a default image path if needed
        }
        
        // Find user based on user_id
        $user = \App\Models\User::find($validatedData['user_id']);

        // Create new Recipe
        $blogs = new \App\Models\Blog();
        $blogs->name = $validatedData['name'];
        $blogs->category = $validatedData['category'];
        $blogs->description = $validatedData['description'];
        $blogs->author_id = $user->id;
        $blogs->image_url = $imagePath ? '/storage/' . $imagePath : null;
        
        // Default rating (optional, you can adjust or add a rating input)
        $blogs->rating = 0;
    
        $blogs->save();
    
        // Redirect with success message
        return redirect()->route('blog.index')->with('success', 'Blog Post added successfully!');
    }
    
    public function myBlog($user_id)
    {
        // Fetch only recipes created by logged in user, paginate for performance
        $blogs = Blog::where('author_id', $user_id)->latest()->paginate(10);

        return view('blog.my-blog', compact('blog'));
    }

    public function edit($blogID)
    {
        $blogs = Blog::findOrFail($blogID);
        // Authorization: only owner or admin can edit
        if (Auth::user()->id !== $blogs->author_id) {
            \Illuminate\Support\Facades\Log::info('User ID: ' . Auth::user()->id);
            \Illuminate\Support\Facades\Log::info('Recipe User ID: '. $blogs->author_id);
            return redirect()->route('blog.index')->with('failed', 'Unauthorized Access!');
        }

        return view('blog.edit-blog', compact('blog'));
    }

    public function update(Request $request, $blogID)
    {
        $blogs = Blog::findOrFail($blogID);

        // Authorization again
        if (Auth::user()->id !== $blogs->author_id) {
            \Illuminate\Support\Facades\Log::info('User ID: ' . Auth::user()->id);
            \Illuminate\Support\Facades\Log::info('Blog User ID: '. $blogs->author_id);
            return redirect()->route('blog.index')->with('failed', 'Unauthorized Access!');
        }

        // Validation rules (match your recipe fields)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'image_url' => 'nullable|image|max:2048',
        ]);

        // Update data fields
        $blogs->fill($validated);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blog', 'public');

            // Delete old image if exists
            if ($blogs->image_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($blogs->image_url);
            }

            $blogs->image_url = $path;
        }

        $blogs->save();

        return redirect()->route('blog.index', $blogs)->with('success', 'Blog Post updated successfully.');
    }

    public function destroy(Request $request, $blogID)
    {
        \Illuminate\Support\Facades\Log::info('Blog ID: ' . $blogID);
        // Authorize: only owner or admin can delete
        $user = Auth::user();
        $blog = Blog::findOrFail($blogID);

        if (!$user || ($user->id !== $blog->author_id)) {
            return redirect()->route('blog.index')->with('failed', 'Unauthorized Access!');
        }

        // Delete image file from storage if exists
        if ($blog->image_url && \Illuminate\Support\Facades\Storage::disk('public')->exists('blog/' . basename($blog->image_url))) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('blog/' . basename($blog->image_url));
        }

        // Delete recipe record
        $blog->delete();

        // Redirect back with success message
        return redirect()->route('blog.my', $user->id)
                        ->with('success', 'Recipe deleted successfully.');
    }

    public function hasInappropriateReview(): bool
    {
        return $this->reviews->contains(function ($review) {
            return $review->isInappropriate();
        });
    }

}
