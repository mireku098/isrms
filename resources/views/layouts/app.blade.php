<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'en') }}">

<head>
  @include('partials.head-meta')
  <title>@yield('title', 'ISRMS') | Integrated Store & Requisition Management System</title>
  <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" />
  @stack('styles')
</head>

<body>
  <!-- Vertical Sidebar -->
  <div>
    @include('partials.sidebar')

    <!-- Main Content -->
    <div id="content" class="position-relative h-100">
      @include('partials.header')
      
      <!-- container -->
      <div class="custom-container pt-4 pb-10">
        <!-- Flash Messages -->
        @if ($message = Session::get('success'))
          <x-alert type="success" :message="$message" />
        @endif

        @if ($message = Session::get('error'))
          <x-alert type="danger" :message="$message" />
        @endif

        @if ($message = Session::get('warning'))
          <x-alert type="warning" :message="$message" />
        @endif

        @if ($message = Session::get('info'))
          <x-alert type="info" :message="$message" />
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
          <x-alert type="danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </x-alert>
        @endif

        @yield('content')
      </div>
      
      {{-- <footer class="footer mt-auto py-3 bg-light border-top position-fixed bottom-0 w-100 bg-white" style="z-index: 1000;">
          <div class="container text-center">
              <span class="text-muted small">© {{ date('Y') }} ISRMS - Integrated Store & Requisition Management System. All rights reserved.</span>
          </div>
      </footer> --}}
    </div>
  </div>

  @include('partials.scripts')
  @stack('scripts')
</body>
</html>
