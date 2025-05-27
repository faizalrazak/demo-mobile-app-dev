<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
        @vite('resources/js/app.js')
    </flux:main>
</x-layouts.app.sidebar>
