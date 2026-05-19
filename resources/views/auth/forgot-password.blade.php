<!DOCTYPE html>
<html lang="en">
  <head>
    @include('partials.head.head-meta')
    <title>Forgot Password | ISRMS - Integrated Store & Requisition Management System</title>
    @include('partials.head.head-links')
  </head>

  <body>
    <main class="d-flex flex-column justify-content-center vh-100">
      <!--Sign up start-->
      <section>
        <div class="container">
          <div class="row mb-8">
            <div class="col-xl-4 offset-xl-4 col-md-12 col-12">
              <div class="text-center">
                <a href="{{ url('/') }}" class="fs-2 fw-bold d-flex align-items-center gap-2 justify-content-center mb-6 text-decoration-none">
                  <img src="{{ asset('assets/images/brand/logo/logo-icon.svg') }}" alt="" />
                  <span class="text-body-emphasis">ISRMS</span>
                </a>
                <h1 class="mb-1">Forgot Password</h1>
                <p class="mb-0 text-secondary">No worries, we will send you reset instruction.</p>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-12">
              <div class="card card-lg mb-6">
                <div class="card-body p-6">
                  @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                      <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                  @endif

                  <form class="needs-validation mb-5" novalidate method="POST" action="{{ route('auth.send-reset-link') }}">
                    @csrf
                    <div class="mb-3">
                      <label for="forgetEmailInput" class="form-label">
                        Email
                        <span class="text-danger">*</span>
                      </label>
                      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="forgetEmailInput" placeholder="Enter your email" value="{{ old('email') }}" required autofocus />
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="d-grid">
                      <button class="btn btn-primary" type="submit">Reset Password</button>
                    </div>
                  </form>
                  <div class="text-center">
                    <a href="{{ route('auth.login') }}">
                      <span>Back to Login</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!--Sign up end-->
      <div class="position-absolute end-0 bottom-0 m-4">
        <div class="dropdown">
          <button class="btn btn-light btn-icon rounded-circle d-flex align-items-center" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <i class="ti theme-icon-active lh-1"><i class="ti theme-icon ti-sun"></i></i>
            <span class="visually-hidden bs-theme-text">Toggle theme</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true">
                <i class="ti theme-icon ti ti-sun"></i>
                <span class="ms-2">Light</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                <i class="ti theme-icon ti-moon-stars"></i>
                <span class="ms-2">Dark</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                <i class="ti theme-icon ti-circle-half-2"></i>
                <span class="ms-2">Auto</span>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </main>

    @include('partials.scripts')
  </body>
</html>
