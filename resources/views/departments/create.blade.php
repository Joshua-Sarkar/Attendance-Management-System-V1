<x-workflow-layout>
    <x-slot name="header">
        <h1 class="font-display font-medium text-[32px] tracking-wide text-vellum">Create Department</h1>
        <div class="text-[13px] text-vellum-muted mt-1.5 tracking-wide">
            Add a new department group to the organization structure
        </div>
    </x-slot>

    <div class="space-y-6">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-vellum-muted uppercase tracking-wider mb-1.5">
                        Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full bg-surface-raised border border-hairline rounded-md text-vellum px-3 py-2 text-sm focus:ring-1 focus:ring-brass focus:border-brass focus:outline-none mt-1.5">
                    @error('name')
                        <p class="text-burgundy font-mono text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-xs font-semibold text-vellum-muted uppercase tracking-wider mb-1.5">
                        Code
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        required
                        class="w-full bg-surface-raised border border-hairline rounded-md text-brass font-mono px-3 py-2 text-sm focus:ring-1 focus:ring-brass focus:border-brass focus:outline-none mt-1.5">
                    @error('code')
                        <p class="text-burgundy font-mono text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-semibold text-vellum-muted uppercase tracking-wider mb-1.5">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full bg-surface-raised border border-hairline rounded-md text-vellum px-3 py-2 text-sm focus:ring-1 focus:ring-brass focus:border-brass focus:outline-none mt-1.5">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-burgundy font-mono text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <x-primary-button type="submit">
                    Create Department
                </x-primary-button>

                <x-secondary-button onclick="window.location.href='{{ route('departments.index') }}'">
                    Cancel
                </x-secondary-button>
            </div>
        </form>
    </div>
</x-workflow-layout>