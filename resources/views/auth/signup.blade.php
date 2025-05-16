@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Sign Up</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <label class="block mb-2 font-semibold" for="name">Name</label>
        <input id="name" name="name" type="text" required autofocus
               class="w-full px-4 py-2 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />

        <!-- Email -->
        <label class="block mb-2 font-semibold" for="email">Email</label>
        <input id="email" name="email" type="email" required
               class="w-full px-4 py-2 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />

        <!-- Password -->
        <label class="block mb-2 font-semibold" for="password">Password</label>
        <input id="password" name="password" type="password" required
               class="w-full px-4 py-2 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />

        <!-- Confirm Password -->
        <label class="block mb-2 font-semibold" for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required
               class="w-full px-4 py-2 mb-6 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />

        <!-- Role Selection -->
        <label class="block mb-2 font-semibold" for="role">Select Role</label>
        <select id="role" name="role" required
                class="w-full px-4 py-2 mb-6 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option value="">-- Choose your role --</option>
            <option value="admin">Admin</option>
            <option value="writer">Writer</option>
            <option value="public">Public User</option>
        </select>

        <button type="submit"
            class="w-full bg-orange-600 text-white font-bold py-3 rounded hover:bg-orange-700 transition">
            Register
        </button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-orange-600 hover:underline">Log in</a>
    </p>
</div>
@endsection
