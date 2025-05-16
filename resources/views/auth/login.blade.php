@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <label class="block mb-2 font-semibold" for="email">Email</label>
        <input id="email" name="email" type="email" required autofocus
               class="w-full px-4 py-2 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />

        <!-- Password -->
        <label class="block mb-2 font-semibold" for="password">Password</label>
        <input id="password" name="password" type="password" required
               class="w-full px-4 py-2 mb-6 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />

        <button type="submit"
            class="w-full bg-orange-600 text-white font-bold py-3 rounded hover:bg-orange-700 transition">
            Log In
        </button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
        Don't have an account? 
        <a href="{{ route('register') }}" class="text-orange-600 hover:underline">Sign up</a>
    </p>
</div>
@endsection
