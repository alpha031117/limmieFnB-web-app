@extends('layouts.app')

@section('content')
<div
    x-data="{ show: {{ session()->has('error') || session()->has('success') ? 'true' : 'false' }} }"
    x-show="show"
    x-transition
    @click="show = false"
    x-init="setTimeout(() => show = false, 4000)"
    class="fixed top-4 right-4 max-w-xs w-full z-50 cursor-pointer rounded shadow-lg p-4
        {{ session('error') ? 'bg-red-500 text-white' : '' }}
        {{ session('success') ? 'bg-green-500 text-white' : '' }}"
>
    {{ session('error') ?? session('success') }}
</div>

<div class="max-w-6xl mx-auto p-6 font-sans text-gray-800">
    {{-- Top Tags --}}
    <div class="flex gap-2 mb-2">
        <span class="bg-orange-600 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $blog->category }}</span>
    </div>

    {{-- Title --}}
    <h1 class="text-3xl font-bold mb-1">{{ $blog->name }}</h1>
    <p class="text-sm text-gray-600 mb-4 max-w-xl">
        {{ $blog->description }}
    </p>

    {{-- Author and Rating --}}
    <div class="flex items-center gap-4 text-sm text-gray-600 mb-8">
        <div class="flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                <path d="M4 20v-2c0-2.21 3.58-4 8-4s8 1.79 8 4v2"/>
            </svg>
            <span>By <span class="text-orange-600 font-semibold cursor-pointer hover:underline">Author {{ $blog->author->name }}</span></span>
        </div>
        <div class="flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 7l3 6-3 2-3-2 3-6z"/>
            </svg>
            <span>Total: {{ $blog->duration }} min</span>
        </div>
    @php
        $averageRating = $blog->reviews->avg('rating') ?? 0;
        $roundedRating = round($averageRating);
        $ratingCount = $blog->reviews->count();   
    @endphp

    <div class="flex items-center gap-1">
        @for ($i = 1; $i <= 5; $i++)
            <svg class="w-4 h-4 {{ $i <= $roundedRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 15l-5.878 3.09L5.416 11.18 1 7.545l6.09-.538L10 1l2.91 6.007 6.09.538-4.416 3.635 1.293 6.91z"/>
            </svg>
        @endfor
        <span>{{ number_format($averageRating, 1) }}</span>
            {{-- Number of ratings --}}
        <span class="text-gray-500 text-sm">({{ $ratingCount }} {{ Str::plural('rating', $ratingCount) }})</span>
</div>

    </div>

    {{-- Main Image --}}
    @if ($blog->image_url)
        <img src="{{ asset('storage/blog/' . basename($blog->image_url)) }}" alt="{{ $blog->name }}" class="w-full aspect-[8/3] object-cover rounded-lg mb-6" />
    @else
        <div class="w-full aspect-[4/3] bg-gray-200 rounded-lg flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <path d="M21 15l-5-5L5 21"/>
            </svg>
        </div>
    @endif

<!-- Write a Review -->
@auth
@if(auth()->user()->role !== 'admin')
<div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 mt-7">
    <form action="{{ route('blog.store', $blog->id) }}" method="POST">
        @csrf
        <h3 class="text-lg font-semibold mb-3">Write a Review</h3>
        <div x-data="{ rating: 0, hover: 0 }" class="flex items-center space-x-1 mb-3 select-none" role="radiogroup" aria-label="Rating">
        @for ($i = 1; $i <= 5; $i++)
            <label
            class="cursor-pointer text-3xl"
            :class="(hover >= {{ $i }} || (!hover && rating >= {{ $i }})) ? 'text-yellow-400' : 'text-gray-300'"
            @mouseenter="hover = {{ $i }}"
            @mouseleave="hover = 0"
            >
            <input
                type="radio"
                name="rating"
                value="{{ $i }}"
                class="hidden"
                x-model="rating"
                aria-checked="false"
                :aria-checked="rating == {{ $i }} ? 'true' : 'false'"
                role="radio"
            />
            ★
            </label>
        @endfor
        <span class="text-sm text-gray-500 ml-3" x-text="rating ? `You rated ${rating} star${rating > 1 ? 's' : ''}` : 'Select a rating'"></span>
        </div>
        <textarea
            name="comment"
            rows="4"
            placeholder="Share your experience with this blog..."
            class="w-full border border-gray-300 rounded p-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
            required
        ></textarea>

        <button
            type="submit"
            class="mt-4 bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600"
        >
            Submit Review
        </button>
    </form>
</div>
    @else
        <!-- Message for admins -->
        <div class="mt-7">
            
        </div>
    @endif
@endauth

<!-- Display Reviews -->
@foreach ($blogs->reviews ?? [] as $review)
<div class="bg-white border border-gray-200 rounded-lg p-4 mb-4" x-data="{ open: false }">
    <div class="flex items-center gap-3 mb-2">
        <div
            class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500"
        >
            <span class="text-xs font-semibold uppercase">{{ substr($review->user->name, 0, 1) }}</span>
        </div>
        <div class="flex-1">
            <div class="font-semibold text-sm">
                {{ $review->user->name }}
                    @if($review->isInappropriate())
                    <span title="This feedback contains inappropriate content" 
                        class="text-red-600 font-bold text-xl select-none">!</span>
                    @endif
            </div>
            <div class="text-yellow-400">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $review->rating ? '' : 'text-gray-300' }}">★</span>
                @endfor
            </div>
            <span class="text-xs text-gray-400">{{ $review->created_at->format('F d, Y') }}</span>
        </div>
        @if(auth()->id() === $review->user_id)
        <button
            @click="open = true"
            type="button"
            class="text-blue-600 hover:bg-blue-100 hover:text-blue-800 text-sm font-medium px-3 py-1 rounded transition duration-200"
        >
            Edit
        </button>
        @endif
        @if(auth()->id() === $review->user_id || auth()->user()->role === 'admin')
            <form
                action="{{ route('reviews.destroy', $review->id) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this review?');"
                class="inline"
            >
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="text-red-600 hover:bg-red-100 hover:text-red-800 text-sm font-medium px-3 py-1 rounded transition duration-200"
                >
                    Delete
                </button>
            </form>
        @endif
    </div>
    <p class="text-sm text-gray-700">{{ $review->comment }}</p>

    <!-- Modal -->
    <div
        x-show="open"
        class="fixed inset-0 backdrop-blur-sm bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
        @keydown.escape.window="open = false"
    >
        <div class="bg-white rounded p-6 w-full max-w-lg shadow-lg relative" @click.away="open = false">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                Edit Your Review
            </h3>

            <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                @csrf
                @method('PUT')

                <label class="block mb-2 font-semibold">Rating</label>
                <select name="rating" class="border border-gray-300 rounded p-2 mb-4 w-full">
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>

                <label class="block mb-2 font-semibold">Comment</label>
                <textarea
                    name="comment"
                    rows="5"
                    class="w-full border border-gray-300 rounded p-3 mb-4"
                    required
                >{{ old('comment', $review->comment) }}</textarea>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="open = false"
                        class="px-4 py-2 border rounded hover:bg-gray-100"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700"
                    >
                        Update Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


    {{-- Right Sidebar --}}
    <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-8">
        {{-- Author --}}
        <aside class="md:col-span-2 border border-gray-200 rounded p-4 space-y-4">
            <h3 class="font-semibold text-lg mb-2">About the Author</h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M5.121 17.804A8.966 8.966 0 0112 15c2.485 0 4.757 1 6.414 2.618"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold">{{ $blog->author->name }}</div>
                    <div class="text-xs text-gray-500">Professional Chef</div>
                </div>
            </div>
            <p class="text-xs text-gray-600">
                Passionate about {{ $blog->category }} cuisine with over 15 years of experience in top restaurants around the world.
            </p>
            <button class="w-full border border-gray-300 py-2 rounded hover:bg-gray-100 text-sm font-semibold">View Profile</button>
        </aside>

        {{-- Newsletter --}}
        <aside class="md:col-span-2 border border-gray-200 rounded p-4">
            <h3 class="font-semibold text-lg mb-2">Newsletter</h3>
            <p class="text-sm text-gray-600 mb-10">
                Subscribe to get weekly blog updates and cooking tips.
            </p>
            <form action="#" method="POST" class="flex gap-2">
                @csrf
                <input
                    type="email"
                    name="email"
                    placeholder="Your email"
                    class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                    required
                />
                <button
                    type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 transition"
                >
                    Subscribe
                </button>
            </form>
        </aside>
    </div>
</div>


{{-- AlpineJS for tabs --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
