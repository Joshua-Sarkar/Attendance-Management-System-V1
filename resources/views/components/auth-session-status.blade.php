@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-forest font-mono']) }}>
        {{ $status }}
    </div>
@endif
