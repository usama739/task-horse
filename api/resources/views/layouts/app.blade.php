<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
    
    <title>TaskHorse | Modern Task Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen" style="background: rgb(0 9 24);">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <div id="app">
        <nav class="bg-transparent shadow-sm py-3 fixed w-full z-20 top-0 start-0" style="background: rgb(0 9 24);">
            <div class="container mx-auto flex flex-wrap items-center justify-between px-3">
                <a href="{{ url('/tasks') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo4.png') }}" alt="App Logo" style="height: 40px">
                </a>
                <button id="navbar-toggle" class="md:hidden text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <div id="navbarNav" class="w-full md:flex md:items-center md:w-auto hidden md:block mt-4 md:mt-0">
                    <ul class="flex flex-col md:flex-row md:space-x-2 items-center mr-5" id="items">
                        @auth
                            <li>
                                <a class="block py-1 px-3 rounded hover:bg-blue-500 cursor-pointer text-white {{ Request::is('tasks*') ? 'bg-blue-600' : '' }}" href="{{ route('tasks.index') }}">Tasks</a>
                            </li>
                            @can('admin')
                                <li>
                                    <a class="block py-1 px-3 rounded hover:bg-blue-500 cursor-pointer text-white {{ Request::is('users*') ? 'bg-blue-600' : '' }}" href="{{ route('users.index') }}">Users</a>
                                </li>
                                <li>
                                    <a class="block py-1 px-3 rounded hover:bg-blue-500 cursor-pointer text-white {{ Request::is('categories*') ? 'bg-blue-600' : '' }}" href="{{ route('categories.index') }}">Categories</a>
                                </li>
                            @endcan
                        @endauth
                    </ul>
                    <ul class="flex flex-col md:flex-row md:space-x-2 items-center mt-4 md:mt-0 md:ml-auto">
                        @guest
                            @if (Route::has('login'))
                                <li>
                                    <a class="block py-1 px-3 rounded hover:bg-blue-500 text-white cursor-pointer {{ Request::is('login*') ? 'bg-blue-600' : '' }}" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li>
                                    <a class="block py-1 px-3 rounded hover:bg-blue-500 text-white cursor-pointer {{ Request::is('register*') ? 'bg-blue-600' : '' }}" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="relative group">
                                <button id="userDropdown" class="flex items-center gap-2 py-1 px-3 rounded hover:bg-blue-500 text-white focus:outline-none cursor-pointer">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-40 bg-white rounded shadow-lg z-50 hidden group-hover:block" id="userDropdownMenu">
                                    <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-8" style="padding-top: 135px;">
            @yield('content')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init(); // Initialize AOS
        // Navbar toggle for mobile
        $(document).ready(function() {
            $('#navbar-toggle').click(function() {
                $('#navbarNav').toggleClass('hidden');
            });
            // Dropdown for user
            $('#userDropdown').click(function(e) {
                e.stopPropagation();
                $('#userDropdownMenu').toggleClass('hidden');
            });
            $(document).click(function() {
                $('#userDropdownMenu').addClass('hidden');
            });
        });
    </script>
</body>
</html>
