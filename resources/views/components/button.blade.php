@props(['type' => 'button', 'variant' => 'primary', 'size' => ''])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn-' . $variant . ($size ? ' btn-' . $size : '')]) }}>
    {{ $slot }}
</button>
