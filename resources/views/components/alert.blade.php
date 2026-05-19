@props(['type' => 'info', 'message' => null])

<div {{ $attributes->merge(['class' => 'alert alert-' . $type . ' alert-dismissible fade show']) }} role="alert">
    {{ $message ?: $slot }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
