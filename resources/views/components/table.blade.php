@props(['headers' => [], 'class' => ''])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-hover align-middle ' . $class]) }}>
        <thead class="table-light">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="text-uppercase small fw-bold text-secondary">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
