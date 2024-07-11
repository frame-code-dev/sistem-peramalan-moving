<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    {{-- Datatable  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.tailwindcss.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <section class="bg-white dark:bg-gray-900 h-screen sm:flex sm:justify-center sm:items-center min-h-screen">
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12 ">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white leading-5">SISTEM PERAMALAN PENDAPATAN METODE MOVING AVERAGE</h1>
            <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">PADA TOKO BANGUNAN UD. WELIN JAYA MENGGUNAKAN .</p>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                Login
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
            </a>
        </div>
    </section>
</body>
</html>
