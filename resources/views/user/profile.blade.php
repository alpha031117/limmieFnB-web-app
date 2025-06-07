@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-orange-600">My Profile</h1>

     <!-- Success & Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif


    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <div class="flex items-center space-x-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                @if(Auth::user()->profile_photo_path)
                    <img class="h-20 w-20 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="User Avatar">
                @else
                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-2xl">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <!-- User Info -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
                <p class="text-gray-600">{{ Auth::user()->email }}</p>
                @if(isset($recipeCount))
                    <p class="mt-1 text-sm text-gray-500">Recipes created: {{ $recipeCount }}</p>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex space-x-4">
            <a href="{{ route('EditForm') }}"
               class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 transition">
                Edit Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
