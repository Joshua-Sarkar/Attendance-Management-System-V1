<x-workflow-layout>
    <x-slot name="header">
        <h1 class="font-display font-medium text-[32px] tracking-wide text-vellum">{{ __('Profile') }}</h1>
        <div class="text-[13px] text-vellum-muted mt-1.5 tracking-wide">
            Manage your credentials and account configurations
        </div>
    </x-slot>

    <div class="space-y-10">
        <div class="border-b border-hairline pb-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="border-b border-hairline pb-8">
            @include('profile.partials.update-password-form')
        </div>

        <div>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-workflow-layout>
