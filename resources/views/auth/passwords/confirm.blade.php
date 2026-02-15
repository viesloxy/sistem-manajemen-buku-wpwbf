<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin - Confirm Password</title>
    @include('layouts.style-global')
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="{{ asset('assets/images/logo.svg') }}">
                </div>
                <h4>Confirm Password</h4>
                <h6 class="font-weight-light">Please confirm your password before continuing.</h6>
                
                <form class="pt-3" method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">
                    @error('password')
                        <span class="text-danger small mt-1">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="d-flex justify-content-between align-items-center mt-2">
                     @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-link text-primary text-small">Forgot password?</a>
                    @endif
                  </div>

                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">CONFIRM PASSWORD</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('layouts.javascript-global')
  </body>
</html>