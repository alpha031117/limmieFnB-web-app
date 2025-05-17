@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 font-sans text-gray-800">
    {{-- Top Tags --}}
    <div class="flex gap-2 mb-2">
        <span class="bg-orange-600 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $recipe->category }}</span>
        <span class="border border-gray-300 text-xs px-3 py-1 rounded-full">{{ $recipe->difficulty }}</span>
    </div>

    {{-- Title --}}
    <h1 class="text-3xl font-bold mb-1">{{ $recipe->name }}</h1>
    <p class="text-sm text-gray-600 mb-4 max-w-xl">
        {{ $recipe->description }}
    </p>

    {{-- Author and Rating --}}
    <div class="flex items-center gap-4 text-sm text-gray-600 mb-8">
        <div class="flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                <path d="M4 20v-2c0-2.21 3.58-4 8-4s8 1.79 8 4v2"/>
            </svg>
            <span>By <span class="text-orange-600 font-semibold cursor-pointer hover:underline">Chef {{ $recipe->chef_name }}</span></span>
        </div>
        <div class="flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 7l3 6-3 2-3-2 3-6z"/>
            </svg>
            <span>Total: {{ $recipe->duration }} min</span>
        </div>
        <div class="flex items-center gap-1">
            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 15l-5.878 3.09L5.416 11.18 1 7.545l6.09-.538L10 1l2.91 6.007 6.09.538-4.416 3.635 1.293 6.91z"/>
            </svg>
            <span>{{ $recipe->rating }}</span>
        </div>
    </div>

    {{-- Main Image --}}
    @if ($recipe->image_url)
        <img src="{{ asset('storage/recipes/' . basename($recipe->image_url)) }}" alt="{{ $recipe->name }}" class="w-full aspect-[8/3] object-cover rounded-lg mb-6" />
    @else
        <div class="w-full aspect-[4/3] bg-gray-200 rounded-lg flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <path d="M21 15l-5-5L5 21"/>
            </svg>
        </div>
    @endif

    {{-- Time and Servings --}}
    <div class="flex justify-around border-t border-b border-gray-200 py-6 mb-8 text-center text-gray-600">
        <div>
            <div class="text-orange-600 font-semibold mb-1">Prep Time</div>
            <div>{{ $recipe->prep_time }} min</div>
        </div>
        <div>
            <div class="text-orange-600 font-semibold mb-1">Cook Time</div>
            <div>{{ $recipe->cook_time }} min</div>
        </div>
        <div>
            <div class="text-orange-600 font-semibold mb-1">Servings</div>
            <div>{{ $recipe->servings }}</div>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ activeTab: 'instructions' }" class="border border-gray-300 rounded">
        <nav class="flex text-sm text-gray-600 border-b border-gray-300">
            <button @click="activeTab = 'instructions'"
                    :class="activeTab === 'instructions' ? 'border-b-4 border-orange-600 text-orange-600' : 'hover:bg-gray-100'"
                    class="flex-1 py-3 font-semibold">
                Instructions
            </button>
            <button @click="activeTab = 'ingredients'"
                    :class="activeTab === 'ingredients' ? 'border-b-4 border-orange-600 text-orange-600' : 'hover:bg-gray-100'"
                    class="flex-1 py-3 font-semibold">
                Ingredients
            </button>
            <button @click="activeTab = 'nutrition'"
                    :class="activeTab === 'nutrition' ? 'border-b-4 border-orange-600 text-orange-600' : 'hover:bg-gray-100'"
                    class="flex-1 py-3 font-semibold">
                Nutrition
            </button>
        </nav>

        {{-- Instructions --}}
        @php
            // Add a newline BEFORE every number + dot, except at the start of the string
            $formattedInstructions = preg_replace('/(?<!^)(\d+\.)/', "\n$1", $recipe->instruction);
        @endphp
        
        <div class="p-6 text-sm text-gray-700 leading-relaxed" x-show="activeTab === 'instructions'">
            {!! nl2br(e($formattedInstructions)) !!}
        </div>

        {{-- Ingredients --}}
        <div class="p-6 text-sm text-gray-700 leading-relaxed" x-show="activeTab === 'ingredients'" style="display:none;">
            <ul class="list-disc list-inside space-y-1">
                @foreach(explode(',', $recipe->ingredients) as $ingredient)
                    <li>{{ trim($ingredient) }}</li>
                @endforeach
            </ul>
        </div>

        {{-- Nutrition --}}
        @php
        $nutrition = "550 calories, 30g fat, 40g carbohydrates, 15g protein";

        $parts = array_map('trim', explode(',', $nutrition));

        $nutrients = [];

        foreach ($parts as $part) {
            if (preg_match('/(\d+)(g)?\s*([a-zA-Z]+)/i', $part, $matches)) {
                $value = $matches[1] . ($matches[2] ?? ''); // add 'g' if present
                $labelRaw = strtolower($matches[3]);

                // Capitalize and normalize label
                $labelMap = [
                    'calories' => 'Calories',
                    'calorie' => 'Calories',
                    'fat' => 'Fat',
                    'carbohydrates' => 'Carbohydrates',
                    'protein' => 'Protein',
                ];
                $label = $labelMap[$labelRaw] ?? ucfirst($labelRaw);

                $nutrients[] = ['value' => $value, 'label' => $label];
            }
        }
        @endphp

        <div class="p-6 text-sm text-gray-700 leading-relaxed" x-show="activeTab === 'nutrition'" style="display:none;">
            <div class="grid grid-cols-3 gap-6">
                @foreach ($nutrients as $nutrient)
                <div class="flex flex-col items-center bg-gray-50 p-4 rounded-lg border border-gray-300">
                    <span class="text-2xl font-bold text-black">{{ $nutrient['value'] }}</span>
                    <span class="text-sm text-gray-700 mt-1">{{ $nutrient['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Sidebar --}}
    <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-8">
        {{-- Author --}}
        <aside class="md:col-span-1 border border-gray-200 rounded p-4 space-y-4">
            <h3 class="font-semibold text-lg mb-2">About the Author</h3>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                <div>
                    <div class="font-semibold">{{ $recipe->chef_name }}</div>
                    <div class="text-xs text-gray-500">Professional Chef</div>
                </div>
            </div>
            <p class="text-xs text-gray-600">
                Passionate about {{ $recipe->category }} cuisine with over 15 years of experience in top restaurants around the world.
            </p>
            <button class="w-full border border-gray-300 py-2 rounded hover:bg-gray-100 text-sm font-semibold">View Profile</button>
        </aside>

        {{-- You Might Also Like --}}
        <aside class="md:col-span-1 border border-gray-200 rounded p-4 space-y-4">
            <h3 class="font-semibold text-lg mb-2">You Might Also Like</h3>
            @for ($i = 0; $i < 3; $i++)
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gray-300 rounded"></div>
                    <div class="text-xs">
                        <div class="font-semibold">Garlic Butter Shrimp Pasta</div>
                        <div>25 min · Easy</div>
                    </div>
                </div>
            @endfor
        </aside>

        {{-- Newsletter --}}
        <aside class="md:col-span-2 border border-gray-200 rounded p-4">
            <h3 class="font-semibold text-lg mb-2">Newsletter</h3>
            <p class="text-xs text-gray-600 mb-4">
                Subscribe to get weekly recipe updates and cooking tips.
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
