@props(['type' => 'primary', 'text' => ''])

<span {{ $attributes->merge(['class' => 'badge bg-' . $type . '-subtle text-' . $type . '-emphasis']) }}>
    {{ $text ?: $slot }}
</span>
