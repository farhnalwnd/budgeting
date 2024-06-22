<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- template --}}

    <link rel="stylesheet" id="stylesheet" href="{{ asset('assets-dashboard/assets') }}/src/css/style.css">
    <!-- Production css (used in all pages) -->
    {{-- <link rel="stylesheet" href="dist/css/style.css"> --> --}}
    <link rel="stylesheet" href="{{ asset('assets-dashboard/assets') }}/src/css/customizer.css">
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <style>
        /* Add custom styles for the slider */
        .slider-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .slick-slide {
            text-align: center;
            padding: 20px;
        }

        .hover-gradient:hover {
    background-image: linear-gradient(to right, gold, white);
}

        /* Gaya hover untuk tombol */
        .btn-hover:hover {
            background-color: gold;
            /* Warna latar belakang saat hover */
            color: black;
            /* Warna teks saat hover */
            border-color: gold;
            /* Warna border saat hover */
        }
    </style>
    <style>
        .nav-link {
            transition: color 0.3s ease-in-out;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: #fff;
        }

        section {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.3s ease-in-out;
        }

        section.active {
            opacity: 1;
            transform: translateY(0);
        }

        .bg-gold {
            background-color: #c0a01f;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>

<body class="font-sans text-base font-normal text-gray-600 dark:text-gray-400 dark:bg-gray-900 pt-16 lg:pt-20">
    <!-- ========== { HEADER }==========  -->
    <header>
        <!-- Navbar -->
        <nav x-data="{ open: false }"
            class=" nav-top flex flex-nowrap lg:flex-start items-center z-20 fixed top-0 left-0 right-0 overflow-y-auto max-h-screen lg:overflow-visible lg:max-h-full bg-gold dark:bg-indigo-900">
            <div class="container mx-auto px-4 xl:max-w-6xl ">
                <!-- mobile navigation -->
                <div class="flex flex-row justify-between py-3 lg:hidden">
                    <!-- logo -->
                    <a class="flex items-center py-2 mr-4 text-xl" href="/">
                        <h2 class="text-2xl font-semibold text-gray-200 px-4 max-h-9 overflow-hidden">
                            <img class="inline-block w-10 h-auto me-2 -mt-1"
                                src="{{ asset('assets-dashboard/assets') }}/src/img/logosmii.png">
                            </svg><span class="text-gray-200">Intra SMII</span>
                        </h2>
                    </a>

                    <!-- navbar toggler -->
                    <div class="right-0 flex items-center">
                        <!-- Mobile menu button-->
                        <button id="navbartoggle" type="button"
                            class="inline-flex items-center justify-center text-gray-200 focus:outline-none focus:ring-0"
                            aria-controls="mobile-menu" @click="open = !open" aria-expanded="false"
                            x-bind:aria-expanded="open.toString()">
                            <span class="sr-only">Mobile menu</span>
                            <svg x-description="Icon closed" x-state:on="Menu open" x-state:off="Menu closed"
                                class="block h-8 w-8" :class="{ 'hidden': open, 'block': !(open) }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>

                            <svg x-description="Icon open" x-state:on="Menu open" x-state:off="Menu closed"
                                class="hidden h-8 w-8" :class="{ 'block': open, 'hidden': !(open) }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div class="lg:hidden fixed w-full h-full inset-0 z-40" id="mobile-menu" x-description="Mobile menu"
                    x-show="open" style="display: none;">
                    <!-- bg open -->
                    <span class="fixed bg-gray-900 bg-opacity-70 w-full h-full inset-x-0 top-0"></span>

                    <!-- Mobile navbar -->
                    <nav id="mobile-nav"
                        class="flex flex-col end-0 w-64 fixed top-0 py-4 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-400 h-full overflow-auto z-40"
                        x-show="open" @click.away="open=false" x-description="Mobile menu" role="menu"
                        aria-orientation="vertical" aria-labelledby="navbartoggle"
                        x-transition:enter="transform transition-transform duration-300"
                        x-transition:enter-start="ltr:translate-x-full rtl:-translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition-transform duration-300"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="ltr:translate-x-full rtl:-translate-x-full">
                        <div class="mb-auto">
                            <!--logo-->
                            <div class="mh-18 text-center px-12 mb-8">
                                <a href="/" class="flex relative">
                                    <h2 class="text-2xl font-semibold text-gray-200 max-h-9">
                                        <img class="inline-block w-10 h-auto me-2 -mt-1"
                                            src="{{ asset('assets-dashboard/assets') }}/src/img/logosmii.png">

                                        </svg><span class="text-gray-700 dark:text-gray-200">Intra SMII</span>
                                    </h2>
                                </a>
                            </div>

                            <!--navigation-->
                            <div class="mb-4">
                                <nav class="relative flex flex-wrap items-center justify-between">
                                    @if (Route::has('login'))
                                        <div class="grid text-center lg:block my-4 px-4 lg:my-auto">
                                            @auth
                                                <a href="{{ url('/dashboard') }}" wire:navigation
                                                    class="py-2 px-4 text-sm inline-block text-center rounded leading-5 text-gray-100 bg-indigo-500 border border-indigo-500 hover:text-gray-300 hover:bg-indigo-600 hover:ring-0 hover:border-indigo-600 focus:bg-indigo-600 focus:border-indigo-600 focus:outline-none focus:ring-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline me-1"
                                                        width="1.2rem" height="1.2rem" fill="currentColor"
                                                        viewBox="0 0 576 512">
                                                        <circle cx="176" cy="416" r="16"
                                                            style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                                        </circle>
                                                        <circle cx="400" cy="416" r="16"
                                                            style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                                        </circle>
                                                        <path
                                                            d="M280.4 148.3L96 300.1V464a16 16 0 0 0 16 16l112.1-.3a16 16 0 0 0 15.9-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.6a16 16 0 0 0 16 16.1L464 480a16 16 0 0 0 16-16V300L295.7 148.3a12.2 12.2 0 0 0 -15.3 0zM571.6 251.5L488 182.6V44.1a12 12 0 0 0 -12-12h-56a12 12 0 0 0 -12 12v72.6L318.5 43a48 48 0 0 0 -61 0L4.3 251.5a12 12 0 0 0 -1.6 16.9l25.5 31A12 12 0 0 0 45.2 301l235.2-193.7a12.2 12.2 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0 -1.7-16.9z" />

                                                    </svg>Dashboard
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}"
                                                    class=" btn-inside py-2 px-4 text-sm inline-block text-center rounded leading-5 text-gray-100 bg-indigo-500 border border-indigo-500 hover:text-gray-300 hover:bg-indigo-600 hover:ring-0 hover:border-indigo-600 focus:bg-indigo-600 focus:border-indigo-600 focus:outline-none focus:ring-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg"class="inline me-1"
                                                        width="1.2rem" height="1.2rem" fill="currentColor"
                                                        viewBox="0 0 448 512">
                                                        <circle cx="176" cy="416" r="16"
                                                            style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                                        </circle>
                                                        <circle cx="400" cy="416" r="16"
                                                            style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                                        </circle>
                                                        <path
                                                            d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z" />
                                                    </svg> Login
                                                </a>

                                                {{-- @if (Route::has('register'))
                                                    <a href="{{ route('register') }}"
                                                        class="ms-4 btn-inside font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                                        wire:navigate>Register</a>
                                                @endif --}}
                                            @endauth
                                        </div>
                                    @endif
                                    <ul id="side-menu" class="w-full float-none flex flex-col">
                                        <li class="relative">
                                            <a href="#home"
                                                class=" nav-link block py-3 px-4 hover:text-indigo-500 focus:text-indigo-500">Home</a>
                                        </li>
                                        <li class="relative">
                                            <a href="#product"
                                                class="nav-link block py-3 px-4 hover:text-indigo-500 focus:text-indigo-500">Our
                                                Product</a>
                                        </li>
                                        <li class="relative">
                                            <a href="#site"
                                                class="nav-link block py-3 px-4 hover:text-indigo-500 focus:text-indigo-500">Our
                                                Sites</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- copyright -->
                        <div class="mt-5 text-center">
                            <p>Copyright <a href="#">MIS Intra SMII</a> - All right reserved</p>
                        </div>
                    </nav>
                </div><!-- End Mobile menu -->

                <!-- desktop menu -->
                <div class="hidden lg:flex lg:flex-row lg:flex-nowrap lg:items-center lg:justify-between lg:mt-0"
                    id="desktp-menu">
                    <!-- logo -->
                    <a class="hidden lg:flex items-center py-2 mr-4 text-xl" href="index.html">
                        <h2 class="text-2xl font-semibold px-4 max-h-9 overflow-hidden">
                            <img class="inline-block w-10 h-auto me-2 -mt-1"
                                src="{{ asset('assets-dashboard/assets') }}/src/img/logosmii.png">

                            </svg><span class="text-gray-200">Intra SMII</span>
                        </h2>
                    </a>

                    <!-- menu -->
                    <ul class="flex flex-col lg:mx-auto mt-2 lg:flex-row lg:mt-0 text-gray-200">
                        <li class="relative">
                            <a class="nav-link block py-3 lg:py-7 px-6 hover:text-white focus:text-white"
                                href="#home">
                                Home
                            </a>
                        </li>
                        <li class="relative">
                            <a class="nav-link block py-3 lg:py-7 px-6 hover:text-white focus:text-white"
                                href="#product">
                                Our Product
                            </a>
                        </li>
                        <li class="relative">
                            <a class="nav-link block py-3 lg:py-7 px-6 hover:text-white focus:text-white"
                                href="#site">
                                Our Site
                            </a>
                        </li>
                    </ul>

                    <!-- button login -->
                    @if (Route::has('login'))
                        <div class="flex justify-between items-center my-4 lg:my-auto">
                            <div class="grid text-center lg:block">
                                @auth
                                    <a href="{{ url('/dashboard') }}" wire:navigation
                                        class="btn-inside py-2 px-4 text-sm inline-block text-center rounded leading-5 text-gray-100 bg-indigo-500 border border-indigo-500 hover:text-gray-300 hover:bg-indigo-600 hover:ring-0 hover:border-indigo-600 focus:bg-indigo-600 focus:border-indigo-600 focus:outline-none focus:ring-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline me-1" width="1.2rem"
                                            height="1.2rem" fill="currentColor" viewBox="0 0 576 512">
                                            <circle cx="176" cy="416" r="16"
                                                style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                            </circle>
                                            <circle cx="400" cy="416" r="16"
                                                style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                            </circle>
                                            <path
                                                d="M280.4 148.3L96 300.1V464a16 16 0 0 0 16 16l112.1-.3a16 16 0 0 0 15.9-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.6a16 16 0 0 0 16 16.1L464 480a16 16 0 0 0 16-16V300L295.7 148.3a12.2 12.2 0 0 0 -15.3 0zM571.6 251.5L488 182.6V44.1a12 12 0 0 0 -12-12h-56a12 12 0 0 0 -12 12v72.6L318.5 43a48 48 0 0 0 -61 0L4.3 251.5a12 12 0 0 0 -1.6 16.9l25.5 31A12 12 0 0 0 45.2 301l235.2-193.7a12.2 12.2 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0 -1.7-16.9z" />
                                        </svg>Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="btn-inside py-2 px-4  text-sm inline-block text-center rounded leading-5 text-gray-100 bg-indigo-500 border border-indigo-500 hover:text-gray-300 hover:bg-indigo-600 hover:ring-0 hover:border-indigo-600 focus:bg-indigo-600 focus:border-indigo-600 focus:outline-none focus:ring-0">
                                        <svg xmlns="http://www.w3.org/2000/svg"class="inline me-1" width="1.2rem"
                                            height="1.2rem" fill="currentColor" viewBox="0 0 448 512">
                                            <circle cx="176" cy="416" r="16"
                                                style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                            </circle>
                                            <circle cx="400" cy="416" r="16"
                                                style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                            </circle>
                                            <path
                                                d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z" />
                                        </svg> Login
                                    </a>
                                @endauth
                            </div>
                            <div class="relative inline-block w-8 py-3 mt-0.5 me-3 align-middle select-none transition duration-200 ease-in mx-4" style="margin-left: 10px">
                                <input type="checkbox" name="lightdark" id="lightdark"
                                    class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white dark:bg-gray-900 border-2 dark:border-gray-700 appearance-none cursor-pointer">
                                <label for="lightdark"
                                    class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 dark:bg-gray-700 cursor-pointer">
                                    <svg class="sun-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 14.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zM10 1a1 1 0 011 1v1a1 1 0 11-2 0V2a1 1 0 011-1zm0 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm9-8a1 1 0 010 2h-1a1 1 0 110-2h1zM3 10a1 1 0 100 2H2a1 1 0 100-2h1zm13.95-4.536a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM6.464 17.95a1 1 0 01-1.414 0l-.707-.707a1 1 0 011.414-1.414l.707.707a1 1 0 010 1.414zm11.778-11.778a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM4.95 6.464a1 1 0 010 1.414L4.243 8.586a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0z"></path></svg>
                                    <svg class="moon-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 14.707a1 1 0 010-1.414 8 8 0 10-11.313 0 1 1 0 01-1.414-1.414 10 10 0 1113.13 0 1 1 0 01-1.414 1.414h.01z"></path></svg>
                                </label>
                            </div>
                        </div>
                    @endif
                </div><!-- end desktop menu -->
            </div>
        </nav><!-- End Navbar -->
    </header><!-- end header -->

    <!-- =========={ MAIN }==========  -->
    <main id="content">
        <!-- Hero -->
        <div id="home"
            class="relative overflow-hidden bg-gradient-to-b from-indigo-100 to-gray-100 dark:from-indigo-900 dark:to-gray-900">
            <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 py-20">


                <!-- Title -->
                <div class="mb-5 max-w-2xl text-center mx-auto">
                    <h1 class="block font-bold text-gray-800 text-5xl lg:text-6xl dark:text-gray-200">
                        Welcome to <br>
                        <span
                            class="bg-clip-text bg-gradient-to-r from-indigo-700 to-indigo-600 text-transparent">Website
                            Intra SMII</span>
                    </h1>
                </div>

                <div class="mb-8 max-w-3xl text-center mx-auto">
                    <p class="text-lg leading-normal">Website Internal Pt.Sinar Meadow International Indonesia.</p>
                </div>

                <img src="{{ asset('assets-dashboard/assets') }}/src/img/hero2.png" alt="tailwind dashboard"
                    class="max-w-full mx-auto mb-10 ">
            </div>
        </div>
        <!-- End Hero -->

        <!-- =========={ Products }==========  -->
        <div id="product"
            class="relative pt-8 sm:pt-14 pb-2 md:pb-4 bg-gray-100 dark:bg-gray-900 dark:bg-opacity-40">
            <div class="container xl:max-w-6xl mx-auto px-4">
                <!-- section header -->
                <header class="text-center mx-auto mb-12">
                    <h2 class="text-2xl leading-normal mb-2 font-bold text-gray-800 dark:text-gray-300"><span
                            class="font-light">Our</span> Products</h2>
                    <hr class="block w-12 h-0.5 mx-auto my-5 bg-indigo-500 border-indigo-500">
                    <p class="text-gray-500 leading-relaxed font-light text-xl mx-auto pb-2">Our various brands allow
                        us to be the total solution for every customer’s needs while still delivering the highest value.
                    </p>
                </header><!-- end section header -->

                <!-- row -->
                <div class="slider-container flex flex-wrap flex-row -mx-4 text-center">
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb2.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb3.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb4.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb5.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb6.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb7.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb8.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                    <div class=" flex-shrink px-4 max-w-full w-full sm:w-1/2 lg:w-1/3 lg:px-6">
                        <!-- service block -->
                        <div
                            class="p-6 mb-12 shadow-lg rounded-lg bg-white dark:bg-gray-200 transform transition duration-300 ease-in-out hover:-translate-y-2 hover:shadow-xl">
                            <div class="inline-block text-indigo-500 mb-4">
                                <!-- icon -->
                                <img src="{{ asset('assets-dashboard/assets') }}/src/img/products/gb9.jpeg" alt="Image 1">
                            </div>
                        </div> <!-- end service block -->
                    </div>
                </div>
            </div><!-- End features -->

            <!-- =========={ site }==========  -->
            <div id="site"
                class="relative pt-8 sm:pt-14 pb-2 md:pb-4 bg-gray-100 dark:bg-gray-900 dark:bg-opacity-40">
                <div class="container xl:max-w-6xl mx-auto px-4">
                    <!-- section header -->
                    <header class="text-center mx-auto mb-12">
                        <h2 class="text-2xl leading-normal mb-2 font-bold text-gray-800 dark:text-gray-300">
                        </h2>
                        <h2 class="text-2xl leading-normal mb-2 font-bold text-gray-800 dark:text-gray-300"><span
                                class="font-light">Our</span> Websites</h2>
                    </header><!-- end section header -->

                    <div class="flex flex-row flex-wrap justify-center -mx-4 mt-10">
                        <div class="flex-shrink max-w-full w-full md:w-1/2 lg:w-1/3 px-4 md:px-6 mb-8">
                            <div class="transform transition duration-300 ease-in-out hover:-translate-y-2 bg-white dark:bg-gray-700 shadow-lg hover-box-up rounded-lg mb-6 hover-gradient ">
                                <div class="relative overflow-hidden h-60 sm:h-80">
                                    <img src="{{ asset('assets-dashboard/assets') }}/src/img/demo/sinarmeadow.png" class="w-full h-full" title="Tailwind dashboard ecommerce" alt="Tailwind dashboard ecommerce">
                                </div>
                                <div class="mt-4 px-6 py-3">
                                    <div class="flex flex-row w-full items-center justify-between">
                                        <h4 class="text-lg">Sinar Meadow Official</h4>
                                        <a href="https://sinarmeadow.com/" class="py-2 px-4 rounded bg-indigo-500 text-indigo-100 dark:text-indigo-100 dark:bg-indigo-700 ms-2 btn-hover" target="_blank">
                                            <span class="btn-inside">Visit</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink max-w-full w-full md:w-1/2 lg:w-1/3 px-4 md:px-6 mb-8">
                            <div
                                class="transform transition duration-300 ease-in-out hover:-translate-y-2 bg-white dark:bg-gray-700 shadow-lg hover-box-up rounded-lg mb-6 hover-gradient ">
                                <div class="relative overflow-hidden h-60 sm:h-80">
                                    <img src="{{ asset('assets-dashboard/assets') }}/src/img/demo/hris.png" class="w-full h-full"
                                        title="Tailwind dashboard CRM" alt="Tailwind dashboard CRM">
                                </div>
                                <div class="mt-4 px-6 py-3">
                                    <div class="flex flex-row w-full items-center justify-between">
                                        <h4 class="text-lg">Website Andal</h4>
                                        <a href="http://hris.sinarmeadow.com:8081/"
                                            class="py-2 px-4 rounded bg-indigo-500 text-indigo-100 dark:text-indigo-100 dark:bg-indigo-700 ms-2"
                                            target="_blank">
                                            <span class="btn-inside">Visit</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink max-w-full w-full md:w-1/2 lg:w-1/3 px-4 md:px-6 mb-8">
                            <div
                                class="transform transition duration-300 ease-in-out hover:-translate-y-2 bg-white dark:bg-gray-700 shadow-lg hover-box-up rounded-lg mb-6 hover-gradient ">
                                <div class="relative overflow-hidden h-60 sm:h-80">
                                    <img src="{{ asset('assets-dashboard/assets') }}/src/img/demo/emgmt.png" class="max-w-full h-full"
                                        title="Tailwind dashboard cms" alt="Tailwind dashboard cms">
                                </div>
                                <div class="mt-4 px-6 py-3">
                                    <div class="flex flex-row w-full items-center justify-between">
                                        <h4 class="text-lg">Website EMGMT</h4>
                                        <a href="https://emgmt.sinarmeadow.com/loginPage?next=/"
                                            class="py-2 px-4 rounded bg-indigo-500 text-indigo-100 dark:text-indigo-100 dark:bg-indigo-700 ms-2"
                                            target="_blank">
                                            <span class="btn-inside">Visit</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink max-w-full w-full md:w-1/2 lg:w-1/3 px-4 md:px-6 mb-8">
                            <div
                                class="transform transition duration-300 ease-in-out hover:-translate-y-2 bg-white dark:bg-gray-700 shadow-lg hover-box-up rounded-lg mb-6 hover-gradient ">
                                <div class="relative overflow-hidden h-60 sm:h-80">
                                    <img src="{{ asset('assets-dashboard/assets') }}/src/img/demo/wotix.png" class="max-w-full h-full"
                                        title="Tailwind dashboard analytics" alt="Tailwind dashboard analytics">
                                </div>
                                <div class="mt-4 px-6 py-3">
                                    <div class="flex flex-row w-full items-center justify-between">
                                        <h4 class="text-lg">Website WOTIX</h4>
                                        <a href="https://wotix.sinarmeadow.com/login.php"
                                            class="py-2 px-4 rounded bg-indigo-500 text-indigo-100 dark:text-indigo-100 dark:bg-indigo-700 ms-2"
                                            target="_blank">
                                            <span class="btn-inside">Visit</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink max-w-full w-full md:w-1/2 lg:w-1/3 px-4 md:px-6 mb-8">
                            <div
                                class="transform transition duration-300 ease-in-out hover:-translate-y-2 bg-white dark:bg-gray-700 shadow-lg hover-box-up rounded-lg mb-6 hover-gradient ">
                                <div class="relative overflow-hidden h-60 sm:h-80">
                                    <img src="{{ asset('assets-dashboard/assets') }}/src/img/demo/frontend.png"
                                        class="max-w-full h-full" title="Tailwind dashboard analytics"
                                        alt="Tailwind dashboard analytics">
                                </div>
                                <div class="mt-4 px-6 py-3">
                                    <div class="flex flex-row w-full items-center justify-between">
                                        <h4 class="text-lg">Website FRONTEND</h4>
                                        <a href="https://frontend.sinarmeadow.com/smii/login.php"
                                            class="py-2 px-4 rounded bg-indigo-500 text-indigo-100 dark:text-indigo-100 dark:bg-indigo-700 ms-2"
                                            target="_blank">
                                            <span class="btn-inside">Visit</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End demo -->


    </main><!-- end main -->

    <!-- =========={ FOOTER }==========  -->
    <footer class="bg-gray-800 text-gray-400 dark:bg-indigo-950">
        <div class="container xl:max-w-6xl mx-auto px-4 pt-8 pb-5 lg:pb-16">
            <div class="flex flex-wrap flex-row">
                <!-- left widget -->
                <div class="flex-shrink max-w-full w-full lg:w-1/3 px-4 mb-7 lg:mb-0">
                    <!-- Footer Content -->
                    <div class="leading-relaxed">
                        <h4 class="font-semibold text-xl mb-6 text-gray-300 align-middle">About Us</h4>
                        <p class="mb-3">Sinar Meadow yang merupakan hasil kerja sama dari Sinar Mas Grup dan Goodman
                            Fielder adalah perusahaan yang terdepan di Indonesia dalam bidang penghasil lemak nabati
                            (edible fat).
                        </p>
                        <address class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block me-1" width="1.2rem"
                                height="1.2rem" viewbox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M256,48c-79.5,0-144,61.39-144,137,0,87,96,224.87,131.25,272.49a15.77,15.77,0,0,0,25.5,0C304,409.89,400,272.07,400,185,400,109.39,335.5,48,256,48Z"
                                    style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                </path>
                                <circle fill="currentColor" cx="256" cy="192" r="48"
                                    style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                </circle>
                            </svg>
                            Kawasan Industri Pulogadung, <br>
                            Jl. Pulo Ayang I No. 6. Jakarta 13260
                        </address>
                        <p class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block me-1" width="1.2rem"
                                height="1.2rem" viewbox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M451,374c-15.88-16-54.34-39.35-73-48.76C353.7,313,351.7,312,332.6,326.19c-12.74,9.47-21.21,17.93-36.12,14.75s-47.31-21.11-75.68-49.39-47.34-61.62-50.53-76.48,5.41-23.23,14.79-36c13.22-18,12.22-21,.92-45.3-8.81-18.9-32.84-57-48.9-72.8C119.9,44,119.9,47,108.83,51.6A160.15,160.15,0,0,0,83,65.37C67,76,58.12,84.83,51.91,98.1s-9,44.38,23.07,102.64,54.57,88.05,101.14,134.49S258.5,406.64,310.85,436c64.76,36.27,89.6,29.2,102.91,23s22.18-15,32.83-31a159.09,159.09,0,0,0,13.8-25.8C465,391.17,468,391.17,451,374Z"
                                    style="fill:none;stroke:currentColor;stroke-miterlimit:10;stroke-width:32px">
                                </path>
                            </svg>
                            Telp (+62-21) 4602981-85 / (+62-21) 4601935
                        </p>
                        <a href="mailto:cs@sinarmeadow.com" class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block me-1" width="1.2rem"
                                height="1.2rem" viewbox="0 0 512 512">
                                <rect fill="currentColor" x="48" y="96" width="416" height="320" rx="40"
                                    ry="40"
                                    style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                </rect>
                                <polyline fill="currentColor" points="112 160 256 272 400 160"
                                    style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                </polyline>
                            </svg>
                            cs@sinarmeadow.com
                        </a>
                    </div>
                </div>

                <!-- center widget -->
                <div class="flex-shrink max-w-full w-full lg:w-1/3 px-4 mb-7 lg:mb-0">

                </div>

                <!-- right widget -->
                <div class="flex-shrink max-w-full w-full lg:w-1/3 px-4 mb-7 lg:mb-0">
                    <!-- Footer Content -->
                    <div class="leading-relaxed">
                        <h4 class="font-semibold text-xl mb-6 text-gray-300">Popular Links</h4>
                        <div class="flex flex-wrap flex-row -mx-4">
                            <div class="flex-shrink max-w-full w-1/2 px-4">
                                <ul class="space-y-2">
                                    <li><a class="hover:text-gray-300" href="https://sinarmeadow.com">SinarMeadow
                                            Official</a></li>
                                    <li><a class="hover:text-gray-300" href="http://hris.sinarmeadow.com:8081/">Andal
                                            Linkage</a></li>
                                    <li><a class="hover:text-gray-300"
                                            href="https://emgmt.sinarmeadow.com/loginPage?next=/">EMGMT WEB</a></li>
                                    <li><a class="hover:text-gray-300"
                                            href="https://wotix.sinarmeadow.com/login.php">WOTIX WEB</a></li>
                                    <li><a class="hover:text-gray-300"
                                            href="https://frontend.sinarmeadow.com/smii/login.php">FRONTEND WEB</a>
                                    </li>
                                </ul>
                            </div>
                            {{-- <div class="flex-shrink max-w-full w-1/2 px-4">
                                <ul class="space-y-2">
                                    <li><a class="hover:text-gray-300" href="#">Latest post</a></li>
                                    <li><a class="hover:text-gray-300" href="#">Popular post</a></li>
                                    <li><a class="hover:text-gray-300" href="#">Blogs</a></li>
                                    <li><a class="hover:text-gray-300" href="#">Events</a></li>
                                    <li><a class="hover:text-gray-300" href="#">Fax</a></li>
                                    <li><a class="hover:text-gray-300" href="#">Category</a></li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                </div><!-- end right widget -->
            </div>
        </div>
        <!-- copyright  -->
        <div class="container xl:max-w-6xl mx-auto px-4">
            <div class="flex flex-wrap lg:flex-row -mx-4 py-9">
                <div class="w-full text-center">
                    <p>Copyright PT. SINAR MEADOW | MIS | All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    {{-- <!-- Customizer (Only for demo purpose) -->
    <div x-data="{ open: false }" class="relative">
        <a href="javascript:;"
            class="fixed bottom-4 end-4 text-gray-500 py-3 px-4 flex text-sm rounded-full focus:outline-none"
            aria-controls="mobile-canvas" @click="open = !open" aria-expanded="false">
            <span class="sr-only">Customizer</span>
            <svg x-description="Icon closed" x-state:on="Menu open" x-state:off="Menu closed"
                class="block h-6 w-6 animate-spin-slow" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewbox="0 0 16 16">
                <path
                    d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z">
                </path>
                <path
                    d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z">
                </path>
            </svg>
            <!-- <i class="text-2xl fas fa-cog"></i> -->
        </a>

        <!-- Right Offcanvas -->
        <div class="fixed w-full h-full inset-0 z-50" id="mobile-canvas" x-description="Mobile menu" x-show="open"
            style="display: none;">
            <!-- bg open -->
            <span class="fixed bg-gray-900 bg-opacity-70 w-full h-full inset-x-0 top-0"></span>

            <nav id="mobile-nav"
                class="flex flex-col end-0 w-72 fixed top-0 bg-white dark:bg-gray-800 h-full overflow-auto z-40 scrollbars show"
                x-show="open" @click.away="open=false" x-description="Mobile menu" role="menu"
                aria-orientation="vertical" aria-labelledby="navbartoggle"
                x-transition:enter="transform transition-transform duration-300"
                x-transition:enter-start="ltr:translate-x-full rtl:-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition-transform duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="ltr:translate-x-full rtl:-translate-x-full">
                <div class="p-6 bg-indigo-500 text-gray-100 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-row justify-between">
                        <h3 class="text-md font-bold">Customizer</h3>
                        <button @click="open = false" type="button" class="inline-block w-4 h-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                class="inline-block text-gray-100 bi bi-x-lg" viewbox="0 0 16 16" id="x-lg">
                                <path
                                    d="M1.293 1.293a1 1 0 011.414 0L8 6.586l5.293-5.293a1 1 0 111.414 1.414L9.414 8l5.293 5.293a1 1 0 01-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 01-1.414-1.414L6.586 8 1.293 2.707a1 1 0 010-1.414z">
                                </path>
                            </svg>
                            <!-- <i class="fas fa-times"></i> -->
                        </button>
                    </div>
                </div>
                <div class="py-3 px-6 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-base text-semibold">Color Scheme</p>
                    <div class="flex flex-row">
                        <div
                            class="relative inline-block w-8 py-3 mt-0.5 me-3 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="lightdark" id="lightdark"
                                class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white dark:bg-gray-900 border-2 dark:border-gray-700 appearance-none cursor-pointer">
                            <label for="lightdark"
                                class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 dark:bg-gray-700 cursor-pointer"></label>
                        </div>
                        <p class="text-sm text-gray-500 self-center">Light and Dark</p>
                    </div>
                </div>
                <div class="py-3 px-6 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-base text-semibold">Sidebar Color</p>
                    <div class="flex flex-row">
                        <div
                            class="relative inline-block w-8 py-3 mt-0.5 me-3 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="sidecolor" id="sidecolor"
                                class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white dark:bg-gray-900 border-2 dark:border-gray-700 appearance-none cursor-pointer">
                            <label for="sidecolor"
                                class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 dark:bg-gray-700 cursor-pointer"></label>
                        </div>
                        <p class="text-sm text-gray-500 self-center">Light and Dark</p>
                    </div>
                </div>
                <div class="py-3 px-6 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-base text-semibold">Direction</p>
                    <div class="flex flex-row">
                        <div
                            class="relative inline-block w-8 py-3 mt-0.5 me-3 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="rtlmode" id="rtlmode"
                                class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white dark:bg-gray-900 border-2 dark:border-gray-700 appearance-none cursor-pointer">
                            <label for="rtlmode"
                                class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 dark:bg-gray-700 cursor-pointer"></label>
                        </div>
                        <p class="text-sm text-gray-500 self-center">LTR and RTL</p>
                    </div>
                </div>
                <div class="py-3 px-6 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-base text-semibold">Layout</p>
                    <div class="relative mb-3">
                        <a href="index.html"
                            class="inline-block py-2 px-2.5 mt-2 rounded text-sm text-gray-500 bg-gray-100 dark:bg-gray-900 dark:bg-opacity-20 dark:hover:bg-opacity-60 hover:text-indigo-500 hover:bg-gray-200 self-center">Default</a>
                        <a href="layout-compact.html"
                            class="inline-block py-2 px-2.5 mt-2 rounded text-sm text-gray-500 bg-gray-100 dark:bg-gray-900 dark:bg-opacity-20 dark:hover:bg-opacity-60 hover:text-indigo-500 hover:bg-gray-200 self-center">Compact</a>
                        <a href="layout-topnav.html"
                            class="inline-block py-2 px-2.5 mt-2 rounded text-sm text-gray-500 bg-gray-100 dark:bg-gray-900 dark:bg-opacity-20 dark:hover:bg-opacity-60 hover:text-indigo-500 hover:bg-gray-200 self-center">Topnav</a>
                    </div>
                </div>
                <div id="customcolor" class="py-3 px-6 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-base text-semibold">Primary Color</p>
                    <div class="relative my-3">
                        <div id="custred" title="red"
                            class="inline-block p-3 me-1.5  bg-red-500 hover:opacity-90 rounded-full cursor-pointer">
                        </div>
                        <div id="custindigo" title="indigo"
                            class="inline-block p-3 me-1.5  bg-indigo-500 hover:opacity-90 rounded-full cursor-pointer">
                        </div>
                        <div id="custgreen" title="green"
                            class="inline-block p-3 me-1.5  bg-green-500 hover:opacity-90 rounded-full cursor-pointer">
                        </div>
                        <div id="custblue" title="blue"
                            class="inline-block p-3 me-1.5  bg-blue-500 hover:opacity-90 rounded-full cursor-pointer">
                        </div>
                        <div id="custpurple" title="purple"
                            class="inline-block p-3 me-1.5  bg-purple-500 hover:opacity-90 rounded-full cursor-pointer">
                        </div>
                        <div id="custindigo" title="indigo"
                            class="inline-block p-3 me-1.5  bg-indigo-500 hover:opacity-90 rounded-full cursor-pointer">
                        </div>
                        <div id="custindigo" title="reset color" class="inline-block cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-arrow-counterclockwise" viewbox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"></path>
                                <path
                                    d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
               
            </nav>
        </div>
    </div><!-- End Customizer (Only for demo purpose) --> --}}

    <!--start::Global javascript (used in all pages)-->
    <script src="{{ asset('assets-dashboard/assets') }}/vendors/alpinejs/dist/cdn.min.js"></script><!-- core js -->
    <script src="{{ asset('assets-dashboard/assets') }}/vendors/flatpickr/dist/flatpickr.min.js"></script><!-- input date -->
    <script src="{{ asset('assets-dashboard/assets') }}/vendors/flatpickr/dist/plugins/rangePlugin.js"></script><!-- input range date -->
    <script src="{{ asset('assets-dashboard/assets') }}/vendors/%40yaireo/tagify/dist/tagify.min.js"></script><!-- input tags -->
    <script src="{{ asset('assets-dashboard/assets') }}/vendors/pristinejs/dist/pristine.min.js"></script><!-- form validation -->
    <script src="{{ asset('assets-dashboard/assets') }}/vendors/simple-datatables/dist/umd/simple-datatables.js"></script><!--sort table-->
    <!--end::Global javascript (used in all pages)-->

    <!-- Minify Global javascript (for production purpose) -->
    <!-- <script src="dist/js/scripts.js"></script> -->
    <!--start::Demo javascript ( initialize global javascript )-->
    <script src="{{ asset('assets-dashboard/assets') }}/src/js/demo.js"></script>

    <script src="{{ asset('assets-dashboard/assets') }}/vendors/flickity/dist/flickity.pkgd.min.js"></script><!-- slider -->

    <!--start::Customizer js ( Only for demo purpose )-->
    <script src="{{ asset('assets-dashboard/assets') }}/src/js/customizer.js"></script>

    <!-- Add Slick slider library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.slider-container').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                dots: false,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }]
            });
        });
    </script>
</body>

</html>
