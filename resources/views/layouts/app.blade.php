<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        >

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
        >
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        >
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
            rel="stylesheet"
        >

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="relative flex min-h-screen flex-col bg-background font-sans text-foreground antialiased">
        @include('layouts.navigation')

        <main class="flex-1">
            <!-- Page Header -->
            @isset($header)
                <section class="bg-gradient-to-b from-black/60 via-black/40 to-black/20">
                    <div class="h-16"></div>
                    <div
                        class="container grid h-[35vh] place-items-center text-center font-serif text-background dark:text-foreground">
                        {{ $header }}
                    </div>
                </section>
            @else
                <div class="h-16"></div>
            @endisset

            <!-- Page Content -->
            <div class="container py-12">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
