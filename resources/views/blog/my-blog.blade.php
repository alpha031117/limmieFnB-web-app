@extends('layouts.app')

@section('content')

<!-- Alpine.js CDN -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Success Toast -->
@if(session('success'))
<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => show = false, 4000)"
    x-transition 
    class="fixed top-5 right-5 z-50 max-w-sm w-full bg-white border border-gray-300 rounded-lg shadow-lg p-4 flex items-center space-x-3"
    role="alert"
>
    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    <div class="flex-1">
        <p class="text-sm font-semibold text-gray-900">Successfully saved!</p>
        <p class="text-sm text-gray-500">{{ session('success') }}</p>
    </div>
    <button @click="show = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
</div>
@endif

<!-- Main Blog Container -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Blog</h1>
    <div class="flex justify-between items-center mb-4">
        <p class="text-gray-600">Explore our collection of articles, stories, and tips about food and cooking.</p>
        <a href="{{ route('blog.create') }}">
            <button class="bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700 transition">Add Blog</button>
        </a>
    </div>
    <hr class="border-gray-300 mb-4" />

    <!-- Search & Sort -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <input 
            type="search" 
            id="searchInput" 
            placeholder="Search recipes..." 
            onkeyup="filterBlog()"
            class="border rounded px-4 py-2 w-full md:w-1/3 border-gray-300 focus:border-gray-600 focus:outline-none" 
        />

        <!-- Sort Dropdown -->
        <div x-data="{ open: false, selected: 'Newest' }" class="relative">
            <button 
                @click="open = !open"
                class="flex items-center justify-between w-48 px-4 py-2 text-sm font-medium border border-gray-300 bg-white rounded-md shadow-sm hover:bg-gray-50"
            >
                <span x-text="selected"></span>
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div 
                x-show="open" 
                @click.outside="open = false"
                x-transition 
                class="absolute right-0 z-10 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
            >
                <div class="py-1">
                    <template x-for="option in ['Newest', 'Most Populars']" :key="option">
                        <a 
                            href="#" 
                            @click.prevent="selected = option; open = false; $nextTick(() => { sortBlog(selected); })"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            :class="{ 'bg-gray-100 font-semibold': selected === option }"
                        >
                            <svg 
                                x-show="selected === option" 
                                class="mr-2 w-4 h-4 text-orange-600" 
                                xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                            <span x-text="option"></span>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Filters -->
    <div id="filters" class="flex flex-wrap gap-3 mb-8">
        @php
            $filters = ['All', 'Recipes', 'Cuisine Types', 'Diest & Lifestyles', 'Cooking Techniques', 'Tips & Tricks', 'Travel & Food'];
        @endphp
        @foreach ($filters as $filter)
            <button
                onclick="filterCategory('{{ strtolower($filter) }}', this)"
                class="filter-btn px-4 py-1 text-sm border rounded border-gray-300 hover:bg-orange-500 hover:text-white flex items-center space-x-2"
            >
                <svg class="tick-icon hidden w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ $filter }}</span>
            </button>
        @endforeach
    </div>

    <!-- Blog Grid -->
    <div id="blogGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @foreach ($blogs as $blog)
            <a 
                href="{{ route('blog.show', $blog->id) }}" 
                class="bg-white rounded-lg shadow p-4 group hover:shadow-lg transition-shadow"
                data-category="{{ strtolower($blog->category) }}"
                data-created-at="{{ $blog->created_at->timestamp }}"
            >
                <!-- Badge -->
                <span class="absolute top-3 right-3 bg-orange-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $blog->category }}
                </span>

                <!-- Image -->
                <div class="aspect-[4/3] bg-gray-200 rounded mb-4 flex items-center justify-center text-gray-400 overflow-hidden">
                    @if ($blog->image_url)
                        <img 
                            src="{{ asset('storage/blog/' . basename($blog->image_url)) }}" 
                            alt="{{ $blog->name }}" 
                            class="object-cover w-full h-full rounded group-hover:scale-105 transition-transform duration-300"
                        />
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7m-4 0L12 13 7 7m7 0V3" />
                        </svg>
                    @endif
                </div>

                <!-- Content -->
                <h3 class="font-bold text-lg mb-1">{{ $blog->name }}</h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $blog->description }}</p>
            </a>
        @endforeach
    </div>
</div>

<!-- JS Filter & Sort -->
<script>
    let activeCategory = 'all';

    function filterBlog() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const blogCards = document.querySelectorAll('#blogGrid a');

        blogCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const category = card.getAttribute('data-category');
            const matchesSearch = title.includes(input);
            const matchesCategory = activeCategory === 'all' || category === activeCategory;

            card.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
        });
    }

    function filterCategory(category, btn) {
        activeCategory = category;
        document.querySelectorAll('.filter-btn .tick-icon').forEach(el => el.classList.add('hidden'));
        btn.querySelector('.tick-icon').classList.remove('hidden');
        filterBlog();
    }

    function sortBlog(option) {
        const grid = document.getElementById('blogGrid');
        const cards = Array.from(grid.children);

        if (option === 'Newest') {
            cards.sort((a, b) => b.dataset.createdAt - a.dataset.createdAt);
        }
        // Add more sort logic (e.g., Popular) if data exists

        grid.innerHTML = '';
        cards.forEach(card => grid.appendChild(card));
    }
</script>

@endsection
