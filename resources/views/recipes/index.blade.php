@extends('layouts.app')

@section('content')
@if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 4000)"
        x-transition 
        class="fixed top-5 right-5 z-50 max-w-sm w-full bg-white border border-gray-300 rounded-lg shadow-lg p-4 flex items-center space-x-3"
        role="alert"
    >
        <!-- Icon -->
        <svg class="h-6 w-6 text-green-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>

        <!-- Text -->
        <div class="flex-1">
            <p class="text-sm font-semibold text-gray-900">Successfully saved!</p>
            <p class="text-sm text-gray-500">{{ session('success') }}</p>
        </div>

        <!-- Close button -->
        <button 
            @click="show = false"
            class="text-gray-400 hover:text-gray-600 focus:outline-none"
            aria-label="Close notification"
        >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
@endif

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold">Recipes</h1>
    <div class="flex items-center justify-between mb-4">
        <div>
          <p class="text-gray-600">Browse our collection of delicious recipes from around the world.</p>
        </div>
        <a href="{{ route('recipes.create') }}">
          <button type="submit" class="bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700 transition cursor-pointer">
            Add Recipe
          </button>
        </a>
      </div>
      <hr class="border-gray-300 border-t" />
      
    <!-- Search and Sort -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mt-4 mb-6 space-y-4 md:space-y-0">
        <input type="search" placeholder="Search recipes..." class="border rounded px-4 py-2 w-full md:w-1/3 border-gray-300 focus:border-gray-600 focus:outline-none" id="searchInput" onkeyup="filterRecipes()" />
        {{-- Filter Button --}}
        <div class="flex items-center space-x-4">

            <!-- Include Alpine.js CDN for interactivity -->
            <script src="//unpkg.com/alpinejs" defer></script>
    
            <div x-data="{ open: false, selected: 'Newest' }" class="relative inline-block text-left">
                <button 
                    @click="open = !open" 
                    type="button" 
                    class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:border-gray-300 focus:outline-none focus:ring-1"
                    aria-haspopup="true" 
                    aria-expanded="open.toString()"
                >
                    <span x-text="selected"></span>
                    <!-- Chevron Down Icon -->
                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
        
                <!-- Dropdown Menu -->
                <div 
                    x-show="open" 
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="opacity-0 scale-95" 
                    x-transition:enter-end="opacity-100 scale-100" 
                    x-transition:leave="transition ease-in duration-75" 
                    x-transition:leave-start="opacity-100 scale-100" 
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu" 
                    aria-orientation="vertical" 
                    aria-labelledby="menu-button"
                    tabindex="-1"
                >
                    <div class="py-1">
                        <template x-for="option in ['Newest', 'Highest Rated', 'Difficulty (Easy to High)', 'Difficulty (High to Easy)', 'Cooking Time (Low to High)', 'Cooking Time (High to Low)']" :key="option">
                            <a
                                href="#"
                                @click.prevent="selected = option; open = false; $nextTick(() => { sortRecipes(selected); })"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer"
                                role="menuitem"
                                tabindex="-1"
                                :class="{ 'bg-gray-100 font-semibold': selected === option }"
                            >
                            <svg 
                                x-show="selected === option" 
                                class="mr-2 h-4 w-4 text-orange-600 flex-shrink-0" 
                                xmlns="http://www.w3.org/2000/svg" 
                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                            <span x-text="option"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div id="filters" class="flex flex-wrap gap-3 mb-8">
        @php
            $filters = ['All', 'Dessert', 'Lunch', 'Dinner', 'Desserts', 'Vegetarian', 'Vegan', 'Gluten-Free'];
        @endphp
        @foreach ($filters as $filter)
            <button
                class="filter-btn px-4 py-1 text-sm border rounded border-gray-300 hover:bg-orange-500 hover:text-white transition cursor-pointer flex items-center space-x-2"
                onclick="filterCategory('{{ strtolower($filter) }}', this)"
            >
                <!-- Tick icon on the left -->
                <svg class="tick-icon hidden w-4 h-4 text-white flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ $filter }}</span>
            </button>
        @endforeach
    </div>

    {{-- Recipe Grid --}}
    <div id="recipesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @foreach ($recipes as $recipe)
            <a href="{{ route('recipes.show', $recipe->id) }}" 
                class="bg-white rounded-lg shadow p-4 relative group block hover:shadow-lg transition-shadow duration-200" 
                data-category="{{ strtolower($recipe->category) }}" data-created-at="{{ $recipe->created_at->timestamp }}">
                
                <!-- Category badge -->
                <span class="absolute top-3 right-3 bg-orange-600 text-white text-xs font-semibold px-3 py-1 rounded-full z-10">
                    {{ $recipe->category }}
                </span>
            
                <!-- Image container -->
                <div class="bg-gray-200 aspect-[4/3] rounded mb-4 flex items-center justify-center text-gray-400 text-xl overflow-hidden">
                    @if ($recipe->image_url)
                        <img src="{{ asset('storage/recipes/' . basename($recipe->image_url)) }}" 
                            alt="{{ $recipe->name }}" 
                            class="object-cover w-full h-full rounded transition-transform duration-300 group-hover:scale-105" />
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7m-4 0L12 13 7 7m7 0V3" />
                        </svg>
                    @endif
                </div>
            
                <!-- Recipe details -->
                <h3 class="font-bold text-lg mb-1">{{ $recipe->name }}</h3>
                <p class="text-gray-600 text-sm mb-3 max-w-full overflow-hidden break-words line-clamp-3">{{ $recipe->description }}</p>
            
                <div class="flex items-center space-x-3 mb-2">
                    <span class="border border-gray-300 rounded px-2 py-1 text-xs">{{ ucfirst($recipe->difficulty) }}</span>
                    <div class="flex items-center text-gray-600 text-xs space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                        <span>{{ $recipe->duration }} min</span>
                    </div>
                    <div class="flex items-center space-x-1 text-yellow-500 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.286 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.176 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.285-3.97a1 1 0 00-.364-1.118L2.045 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.951-.69l1.285-3.97z" />
                        </svg>
                        <span>{{ $recipe->rating }}</span>
                    </div>
                </div>
            
                <div class="flex items-center text-gray-700 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A8.966 8.966 0 0112 15c2.485 0 4.757 1 6.414 2.618M9 7a4 4 0 118 0 4 4 0 01-8 0z" />
                    </svg>
                    <span>Chef {{ $recipe->chef->name ?? 'Unknown' }}</span>
                </div>
            </a>
        @endforeach
        
        @if ($recipes->isEmpty())
            <div id="recipeEmpty" class="col-span-full text-center text-gray-500 py-8">
                No recipes found
            </div>
        @endif
    </div>
    
</div>

<script>
    // Function to filter recipes based on search input
    function filterRecipes() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const recipes = document.querySelectorAll('#recipesGrid > a');  // <-- fixed selector
        const empty = document.getElementById('recipeEmpty');

        let anyVisible = false;

        recipes.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            if (title.includes(input) || description.includes(input)) {
                card.style.display = '';
                anyVisible = true;
            } else {
                card.style.display = 'none';
            }
        });

        empty.style.display = anyVisible ? 'none' : 'block';
    }

    // Placeholder functions for filtering and sorting
    function filterCategory(category, btn) {
        const recipes = document.querySelectorAll('#recipesGrid > a');  // <-- fixed selector
        const buttons = document.querySelectorAll('#filters button');
        const empty = document.getElementById('recipeEmpty');

        buttons.forEach(b => {
            b.classList.remove('bg-orange-500', 'text-white');
            b.querySelector('.tick-icon').classList.add('hidden');
        });

        btn.classList.add('bg-orange-500', 'text-white');
        btn.querySelector('.tick-icon').classList.remove('hidden');

        let anyVisible = false;

        if (category === 'all') {
            recipes.forEach(card => {
                card.style.display = '';
                anyVisible = true;
            });
        } else {
            recipes.forEach(card => {
                const categoryValue = card.getAttribute('data-category')?.toLowerCase() || '';
                if (categoryValue === category) {
                    card.style.display = '';
                    anyVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });
        }

        empty.style.display = anyVisible ? 'none' : 'block';
    }

    function sortRecipes(selectedOption) {
        // Map selectedOption to internal keys
        let sortBy = 'newest'; // default

        switch (selectedOption.toLowerCase()) {
            case 'newest':
                sortBy = 'newest';
                break;
            case 'highest rated':
                sortBy = 'highestRated';
                break;
            case 'difficulty (easy to high)':
                sortBy = 'difficultyAsc';
                break;
            case 'difficulty (high to easy)':
                sortBy = 'difficultyDesc';
                break;
            case 'cooking time (low to high)':
                sortBy = 'durationAsc';
                break;
            case 'cooking time (high to low)':
                sortBy = 'durationDesc';
                break;
            default:
                sortBy = 'newest';
        }

        const recipesGrid = document.getElementById('recipesGrid');
        const recipeCards = Array.from(recipesGrid.querySelectorAll('a')); // all recipe cards

        // Helper for difficulty ranking
        function getDifficultyRank(difficulty) {
            const ranks = { 'easy': 1, 'medium': 2, 'hard': 3 };
            return ranks[difficulty.toLowerCase()] || 0;
        }

        recipeCards.sort((a, b) => {
            const aDifficulty = a.querySelector('span.border')?.textContent || '';
            const bDifficulty = b.querySelector('span.border')?.textContent || '';

            const aDuration = parseInt(a.querySelector('div.flex.items-center.text-gray-600.text-xs.space-x-1 span')?.textContent) || 0;
            const bDuration = parseInt(b.querySelector('div.flex.items-center.text-gray-600.text-xs.space-x-1 span')?.textContent) || 0;

            const aRating = parseFloat(a.querySelector('div.flex.items-center.text-yellow-500.text-xs span')?.textContent) || 0;
            const bRating = parseFloat(b.querySelector('div.flex.items-center.text-yellow-500.text-xs span')?.textContent) || 0;

            const aCreated = parseInt(a.getAttribute('data-created-at')) || 0;
            const bCreated = parseInt(b.getAttribute('data-created-at')) || 0;

            switch(sortBy) {
                case 'newest':
                    return bCreated - aCreated; // newest first
                case 'highestRated':
                    return bRating - aRating; // highest rating first
                case 'difficultyAsc':
                    return getDifficultyRank(aDifficulty) - getDifficultyRank(bDifficulty);
                case 'difficultyDesc':
                    return getDifficultyRank(bDifficulty) - getDifficultyRank(aDifficulty);
                case 'durationAsc':
                    return aDuration - bDuration;
                case 'durationDesc':
                    return bDuration - aDuration;
                default:
                    return 0;
            }
        });

        // Append sorted cards back to grid
        recipeCards.forEach(card => recipesGrid.appendChild(card));
    }



</script>
@endsection
