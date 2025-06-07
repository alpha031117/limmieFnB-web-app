<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Limmie Food Blog')</title>
    <script src="//unpkg.com/alpinejs" defer></script>


    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
            /* Your Tailwind CSS styles here */
        </style>
    @endif
    
     <!-- Fonts -->
     <link rel="preconnect" href="https://fonts.bunny.net">
     <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Optional: Add your own stylesheets -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ url('/admin') }}" class="text-xl font-bold text-orange-600">Limmie Food Blog</a>
                @else
                    <a href="{{ url('/') }}" class="text-xl font-bold text-orange-600">Limmie Food Blog</a>
                @endif
            @endauth
            <ul class="flex items-center space-x-6">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ url('/admin') }}" class="hover:text-orange-600 {{ request()->is('admin') ? 'text-orange-600 font-semibold' : '' }}">Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ url('/blog') }}" class="hover:text-orange-600 {{ request()->is('blog*') ? 'text-orange-600 font-semibold' : '' }}">Blog Post</a>
                        </li>
                        <li>
                            <a href="{{ url('/admin/recipe-logs') }}" class="hover:text-orange-600 {{ request()->is('admin/recipe-logs') ? 'text-orange-600 font-semibold' : '' }}">Recipe Modification Logs</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ url('/') }}" class="hover:text-orange-600 {{ request()->is('/') ? 'text-orange-600 font-semibold' : '' }}">Home</a>
                        </li>
                        <li>
                            <a href="{{ url('/blog') }}" class="hover:text-orange-600 {{ request()->is('blog*') ? 'text-orange-600 font-semibold' : '' }}">Blog</a>
                        </li>
                        <li>
                            <a href="{{ url('/recipes') }}" class="hover:text-orange-600 {{ request()->is('recipes*') ? 'text-orange-600 font-semibold' : '' }}">Recipes</a>
                        </li>
                    @endif
                @endauth
                @guest
                    <li>
                        <a href="{{ route('login') }}" class="hover:text-orange-600 {{ request()->is('login') ? 'text-orange-600 font-semibold' : '' }}">Login</a>
                    </li>
                @else
                    <li x-data="{ open: false }" class="relative">
                        <button
                            @click="open = !open"
                            class="flex items-center space-x-3 px-3 py-1 rounded-md focus:outline-none cursor-pointer"
                            aria-haspopup="true"
                            :aria-expanded="open.toString()"
                            style="height: 2.5rem;"
                        >
                            <!-- User Icon Circle -->
                            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M5.121 17.804A8.966 8.966 0 0112 15c2.485 0 4.757 1 6.414 2.618"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                        </button>
                        <div
                            x-show="open"
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 mt-1 w-44 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-30"
                            style="display: none;"
                        >
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="font-semibold text-gray-900">{{ auth()->user()->name ?? 'Jane Doe' }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? 'jane.doe@example.com' }}</p>
                            </div>
                            
                            @auth
                                @if(auth()->user()->role !== 'admin')
                                    <a href="{{ route('recipes.my', ['id' => auth()->id()]) }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-orange-100 hover:text-orange-600">
                                        My Recipes
                                    </a>
                                @endif
                                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-orange-100 hover:text-orange-600">
                                    My Profile
                                </a>
                            @endauth
                
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-left text-gray-700 hover:bg-orange-100 hover:text-orange-600 cursor-pointer">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t py-6 mt-auto">
        <div class="container mx-auto px-4 text-center text-gray-600 text-sm">
            &copy; {{ date('Y') }} Limmie Food Blog. All rights reserved.
        </div>
    </footer>

    <!-- Page Specific Scripts -->
    @stack('scripts')

</body>
</html>
