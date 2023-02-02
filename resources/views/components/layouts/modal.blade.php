<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <!-- Scripts -->

        <script src="{{ asset('vendor/translation-organizer/app.js') }}" defer></script>
        <link rel="stylesheet" href="{{ asset('vendor/translation-organizer/app.css') }}">

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-full">
            {{ $slot }}
        </div>

        @livewire('livewire-ui-modal')
        <x-notifications z-index="z-50" />
        <x-dialog z-index="z-50" blur="md" align="center" />

        @stack('scripts')
    </body>
</html>
