<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskHorse | Modern Task Management</title>
    <link rel="shortcut icon" href="/images/favicon.png" type="image/x-icon">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body class="text-white font-sans min-h-screen flex flex-col" style="background: #0e1525;">
    <header class="w-full px-6 py-4 flex justify-between items-center bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 shadow-lg">
        <div class="flex items-center">
            <img src="/images/logo4.png" alt="TaskHorse Logo" class="h-10 w-15">
        </div>
        <nav class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-md font-semibold bg-blue-600 hover:bg-blue-700 transition text-white shadow">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md font-semibold bg-white text-blue-700 hover:bg-gray-100 transition shadow">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md font-semibold bg-blue-500 hover:bg-blue-600 transition text-white shadow">Register</a>
                    @endif
                @endauth
            @endif
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative flex-1 flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 py-24 px-6">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6 bg-gradient-to-r from-blue-400 via-cyan-400 to-green-300 bg-clip-text text-white drop-shadow-lg">
                Stay on Track. Get Things Done.
            </h1>
            <p class="text-lg md:text-2xl text-blue-100 mb-10 font-medium">
                A cloud-powered task management tool built for teams who value speed, simplicity, and clarity
            </p>
            <a href="register" class="inline-block bg-gradient-to-r from-cyan-400 to-blue-600 hover:from-blue-500 hover:to-blue-500 text-white font-bold py-4 px-5 rounded-lg shadow-lg text-lg transition mt-10">Start Free Trial</a>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <div class="rounded-lg px-6 py-3 flex items-center gap-3 shadow" style="background: #1a2238;">
                    <i class="fas fa-users text-blue-300 text-2xl"></i>
                    <span class="text-blue-100 font-semibold">Team Collaboration</span>
                </div>
                <div class="rounded-lg px-6 py-3 flex items-center gap-3 shadow" style="background: #1a2238;">
                    <i class="fas fa-lock text-green-300 text-2xl"></i>
                    <span class="text-green-100 font-semibold">Enterprise Security</span>
                </div>
                <div class="rounded-lg px-6 py-3 flex items-center gap-3 shadow" style="background: #1a2238;">
                    <i class="fas fa-bolt text-yellow-300 text-2xl"></i>
                    <span class="text-yellow-100 font-semibold">Lightning Fast</span>
                </div>
            </div>
        </div>
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/60 via-blue-800/40 to-blue-700/20 blur-2xl"></div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24" style="background: #161f30;">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-white">Features That Empower Your Team</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 text-center mt-10">
                <div class="rounded-xl p-8 shadow-lg hover:scale-105 transition" style="background: #1a2238;">
                    <i class="fas fa-calendar-alt text-5xl text-blue-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Smart Task Scheduling</h3>
                    <p class="text-blue-100">Plan your week with a calendar view, due dates, and reminders.</p>
                </div>
                <div class="rounded-xl p-8 shadow-lg hover:scale-105 transition" style="background: #1a2238;">
                    <i class="fas fa-tasks text-5xl text-yellow-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Timeline Visualization</h3>
                    <p class="text-blue-100">Track progress with an interactive timeline grouped by task date.</p>
                </div>
                <div class="rounded-xl p-8 shadow-lg hover:scale-105 transition" style="background: #1a2238;">
                    <i class="fas fa-chart-line text-5xl text-green-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Statistics & Insights</h3>
                    <p class="text-blue-100">Visual stats for pending, in-progress, and completed tasks.</p>
                </div>
                <div class="rounded-xl p-8 shadow-lg hover:scale-105 transition" style="background: #1a2238;">
                    <i class="fas fa-lock text-green-400 text-5xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Enterprise-Grade Security</h3>
                    <p class="text-green-100">All files are <span class="text-green-300 font-semibold">encrypted and stored in the AWS cloud</span>, ensuring your data is always protected.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section class="py-24 bg-gradient-to-br from-[#1a2238] via-[#161f30] to-[#0e1525]">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-white">How It Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mt-10">
                <div class="flex flex-col items-center">
                    <div class="bg-blue-700 rounded-full p-5 mb-4 shadow-lg">
                        <i class="fas fa-user-plus text-3xl text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-2">1. Sign Up & Invite</h4>
                    <p class="text-blue-100 text-center">
                        Create your account and invite your team. All user data is protected with enterprise-grade security.
                    </p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-blue-700 rounded-full p-5 mb-4 shadow-lg">
                        <i class="fas fa-layer-group text-3xl text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-2">2. Organize & Assign</h4>
                    <p class="text-blue-100 text-center">
                        Create projects, assign tasks, and upload files. Every file is <span class="text-green-300 font-semibold">encrypted and stored in the AWS cloud</span> for maximum security.
                    </p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-blue-700 rounded-full p-5 mb-4 shadow-lg">
                        <i class="fas fa-rocket text-3xl text-white"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-2">3. Track & Succeed</h4>
                    <p class="text-blue-100 text-center">
                        Monitor progress, collaborate, and share files with confidence—your data is always safe with <span class="text-green-300 font-semibold">cloud-based encryption</span>.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Preview Section -->
    {{-- <section class="py-24" style="background: #161f30;">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-white mb-10">Simple, Transparent Pricing</h2>
            <div class="flex flex-col md:flex-row justify-center gap-8">
                <div class="rounded-xl p-8 shadow-lg flex-1 text-center" style="background: #1a2238;">
                    <h3 class="text-xl font-semibold mb-2 text-blue-400">Starter</h3>
                    <div class="text-4xl font-bold mb-4">$9<span class="text-lg font-normal">/mo</span></div>
                    <ul class="text-blue-100 mb-6 space-y-2">
                        <li>Up to 5 users</li>
                        <li>Unlimited tasks</li>
                        <li>Email support</li>
                    </ul>
                    <a href="{{ Route::has('register') ? route('register') : '#' }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-lg transition shadow">Get Started</a>
                </div>
                <div class="rounded-xl p-8 shadow-lg bg-gradient-to-br from-blue-700 to-cyan-500 flex-1 text-center">
                    <h3 class="text-xl font-semibold mb-2 text-white">Pro</h3>
                    <div class="text-4xl font-bold mb-4 text-white">$29<span class="text-lg font-normal">/mo</span></div>
                    <ul class="text-white mb-6 space-y-2">
                        <li>Unlimited users</li>
                        <li>Advanced analytics</li>
                        <li>Priority support</li>
                        <li><span class="inline-flex items-center gap-1 text-green-300"><i class="fas fa-shield-alt"></i> Secure AWS Cloud Storage</span></li>
                    </ul>
                    <a href="{{ Route::has('register') ? route('register') : '#' }}" class="bg-white text-blue-700 font-bold px-5 py-3 rounded-lg hover:bg-gray-100 transition shadow">Start Pro</a>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Call To Action -->
    <section id="signup" class="py-24 bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 text-center">
        <h3 class="text-3xl md:text-4xl font-bold mb-4 text-white">Ready to Supercharge Your Productivity?</h3>
        <p class="text-white text-lg mb-8">Join teams who get more done every day with TaskHorse SaaS.</p>
        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="bg-white text-blue-700 font-bold px-5 py-4 rounded-lg hover:bg-gray-100 transition text-lg shadow-lg">Try It Free</a>
        <div class="mt-8 flex flex-wrap justify-center gap-6">
            <div class="flex items-center gap-2 text-blue-100"><i class="fas fa-check-circle text-green-400"></i> No credit card required</div>
            <div class="flex items-center gap-2 text-blue-100"><i class="fas fa-check-circle text-green-400"></i> Cancel anytime</div>
            <div class="flex items-center gap-2 text-blue-100"><i class="fas fa-lock text-green-400"></i> Encrypted cloud file storage</div>
            <div class="flex items-center gap-2 text-blue-100"><i class="fas fa-check-circle text-green-400"></i> 24/7 support</div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 text-center text-blue-200 text-sm border-t border-blue-900" style="background: #161f30;">
        <div class="mb-2">
            <a href="#features" class="mx-2 hover:underline">Features</a>
            <a href="#" class="mx-2 hover:underline">Pricing</a>
            <a href="#" class="mx-2 hover:underline">Docs</a>
            <a href="#" class="mx-2 hover:underline">Contact</a>
        </div>
        <div class="flex flex-col items-center justify-center gap-2 mt-4">
            <span class="flex items-center gap-2 text-green-300">
                <i class="fas fa-cloud"></i>
                Secure AWS Cloud Storage - All files encrypted in transit and at rest
            </span>
            <span class="flex items-center gap-2 text-blue-200">
                <i class="fas fa-shield-alt"></i>
                Enterprise-grade security for your team’s data
            </span>
        </div>
        <p class="mt-4">&copy; {{ date('Y') }} TaskHorse SaaS. All rights reserved.</p>
    </footer>
</body>
</html>
