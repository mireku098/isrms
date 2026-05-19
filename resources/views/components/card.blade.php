@props(['title' => null, 'footer' => null, 'class' => ''])

<div {{ $attributes->merge(['class' => 'card ' . $class]) }}>
    @if($title)
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $title }}</h5>
            @if(isset($headerAction))
                <div>{{ $headerAction }}</div>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="card-footer border-top bg-light">
            {{ $footer }}
        </div>
    @endif
</div>
