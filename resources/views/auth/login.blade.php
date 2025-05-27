@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-orange-600 text-center">Login</h1>

    <div class="bg-white rounded-lg shadow p-6 max-w-md mx-auto">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold text-gray-700" for="email">Email</label>
                <input id="email" name="email" type="email" required autofocus
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block mb-2 font-semibold text-gray-700" for="password">Password</label>
                <input id="password" name="password" type="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>

            <button type="submit"
                class="w-full bg-orange-600 text-white font-bold py-3 rounded hover:bg-orange-700 transition">
                Log In
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-orange-600 hover:underline">Sign up</a>
        </p>
    </div>
</div>
@endsection
