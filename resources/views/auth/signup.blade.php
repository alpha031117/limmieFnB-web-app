@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-orange-600 text-center">Sign Up</h1>

    <div class="bg-white rounded-lg shadow p-6 max-w-md mx-auto">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold text-gray-700" for="name">Name</label>
                <input id="name" name="name" type="text" required autofocus
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold text-gray-700" for="email">Email</label>
                <input id="email" name="email" type="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold text-gray-700" for="password">Password</label>
                <input id="password" name="password" type="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold text-gray-700" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>

            <!-- Role Selection -->
            <div class="mb-6">
                <label class="block mb-2 font-semibold text-gray-700" for="role">Select Role</label>
                <select id="role" name="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">-- Choose your role --</option>
                    <option value="admin">Admin</option>
                    <option value="writer">Writer</option>
                    <option value="public">Public User</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full bg-orange-600 text-white font-bold py-3 rounded hover:bg-orange-700 transition">
                Register
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-orange-600 hover:underline">Log in</a>
        </p>
    </div>
</div>
@endsection
