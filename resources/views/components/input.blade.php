@props(['label' => null, 'name', 'type' => 'text', 'placeholder' => '', 'value' => '', 'error' => null])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-semibold small text-secondary">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           id="{{ $name }}" 
           class="form-control @if($error) is-invalid @endif" 
           placeholder="{{ $placeholder }}" 
           value="{{ $value }}"
           {{ $attributes }}>
    @if($error)
        <div class="invalid-feedback">{{ $error }}</div>
    @endif
</div>
