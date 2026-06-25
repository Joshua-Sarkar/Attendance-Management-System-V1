<x-app-layout>
    @isset($header)
        <x-slot name="header">
            {{ $header }}
        </x-slot>
    @endisset

    <!-- Dossier Layout: Sidebar folder tab navigation + main details content + optional summary card -->
    <div class="flex flex-col lg:flex-row gap-8" x-data="{ activeSection: (window.location.hash && ['identity','personnel','employment','contact','emergency','banking','payroll','timeline','history','corrections'].includes(window.location.hash.substring(1))) ? window.location.hash.substring(1) : 'identity' }">
        <!-- Sidebar folder tabs (180px wide) -->
        @isset($tabs)
            <aside class="w-full lg:w-[180px] shrink-0 flex flex-row lg:flex-col gap-1 overflow-x-auto lg:overflow-x-visible lg:sticky lg:top-8 self-start">
                {{ $tabs }}
            </aside>
        @endisset

        <!-- Center details pane -->
        <div class="flex-1 bg-surface border border-hairline rounded p-7 min-w-0">
            {{ $slot }}
        </div>

        <!-- Right summary pane -->
        @isset($summary)
            <div class="w-full lg:w-[280px] shrink-0 lg:sticky lg:top-8 self-start">
                {{ $summary }}
            </div>
        @endisset
    </div>
</x-app-layout>
