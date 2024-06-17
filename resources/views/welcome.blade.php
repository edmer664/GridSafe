<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GridSafe</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    {{-- Navbar --}}
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-5">
            <div class="flex justify-between items-center">
                <a href="#" class="text-2xl font-bold flex-1 text-green-500">
                    <span class="text-blue-500">
                        Grid
                    </span>

                    Safe



                </a>
                <div class="flex items-center justify-evenly flex-1">
                    <a href="/"
                        class="text-gray-800 text-sm mx-3 hover:font-bold duration-200 transition-all">Home</a>
                    <a href="#features"
                        class="text-gray-800 text-sm mx-3 hover:font-bold duration-200 transition-all">Features</a>
                    <a href="#contact"
                        class="text-gray-800 text-sm mx-3 hover:font-bold duration-200 transition-all">Contact</a>
                </div>
                <div class="flex-1 flex justify-end">
                    <a href="{{ route('login') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm mx-3 hover:bg-blue-600 duration-200 transition-all">Login</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="container mx-auto p-5 my-10 grid grid-cols-1 lg:grid-cols-2 gap-5 items-center">
        {{-- IMAGE --}}
        <div class="lg:hidden">
            <img src="{{ asset('hero.png') }}" alt="hero" class="w-full h-full object-contain max-h-96">
        </div>

        {{-- TEXT --}}
        <div class="flex flex-col justify-center">
            <h1 class="text-6xl lg:text-8xl font-bold text-gray-800">Resilency and Efficiency Enhaced with GridSafe</h1>
            <p class="text-lg text-gray-600 mt-2">A trusted platform for monitoring and managing your grid</p>
            <div class="flex items-center gap-5">
                <a href="{{ route('login') }}"
                    class="text-center uppercase bg-blue-500 text-white px-6 py-4 rounded-md text-sm mt-5 hover:bg-blue-600 duration-200 transition-all">Get
                    Started</a>
            </div>
        </div>

        {{-- IMAGE --}}
        <div class="hidden lg:block">
            <img src="{{ asset('hero.png') }}" alt="hero" class="w-full h-full">
        </div>

    </section>

    {{-- Features --}}
    <section 
    id="features"
    class="container mx-auto p-5 my-20 grid grid-cols-1 lg:grid-cols-3 gap-5 items-center">

        {{-- header 3 colspan --}}
        <div class="col-span-1 lg:col-span-3">
            <h1 class="text-4xl font-bold text-gray-800">Features</h1>
            <p class="text-lg text-gray-600 mt-2">Built with the latest technology to make your grid management easier</p>
        </div>

        {{-- feature 1 --}}
        <div class="flex flex-col items-center shadow-lg rounded-lg bg-white border py-10 px-2">
            <img src="{{ asset('monitor.svg') }}" alt="feature1" class="w-52 h-52 object-contain">
            <h1 class="text-2xl font-bold text-gray-800 mt-5">Real-time Monitoring</h1>
            <p class="text-lg text-gray-600 mt-2 text-center">Monitor your grid in real-time to ensure everything is running smoothly</p>
        </div>

        {{-- feature 2 --}}
        <div class="flex flex-col items-center shadow-lg rounded-lg bg-white border py-10 px-2">
            <img src="{{ asset('data.svg') }}" alt="feature2" class="w-52 h-52 object-contain">
            <h1 class="text-2xl font-bold text-gray-800 mt-5">Data Analysis</h1>
            <p class="text-lg text-gray-600 mt-2 text-center">Analyze data to make informed decisions and improve your grid</p>
        </div>

        {{-- feature 3 --}}
        <div class="flex flex-col items-center shadow-lg rounded-lg bg-white border py-10 px-2">
            <img src="{{ asset('man.svg') }}" alt="feature3" class="w-52 h-52 object-contain">
            <h1 class="text-2xl font-bold text-gray-800 mt-5">User Management</h1>
            <p class="text-lg text-gray-600 mt-2 text-center">Manage users and their permissions to ensure security</p>
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact"
    class="container mx-auto p-5 my-20 grid grid-cols-1 items-center text-center">

        {{-- TEXT --}}
        <div class="flex flex-col justify-center items-center">
            <h1 class="text-4xl font-bold text-gray-800">Contact Us</h1>
            <p class="text-lg text-gray-600 mt-2">Have any questions or concerns? Feel free to reach out to us</p>
            <div class="flex items-center gap-5">
                <a href="mailto:e.pizarra@pocketdevs.ph"
                    class="text-center uppercase bg-blue-500 text-white px-6 py-4 rounded-md text-sm mt-5 hover:bg-blue-600 duration-200 transition-all">Email Us</a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white py-10">
        <div class="container mx-auto p-5 flex flex-col items-center justify-center">
            <p class="text-center">GridSafe &copy; 2021. All rights reserved</p>
        </div>
    </footer>

</body>

</html>
