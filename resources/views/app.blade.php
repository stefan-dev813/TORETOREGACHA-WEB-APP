<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0">
        <meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
