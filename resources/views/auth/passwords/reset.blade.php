<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin - Reset Password</title>
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
                <h4>Reset Password</h4>
                <h6 class="font-weight-light">Create your new password.</h6>
                
                <form class="pt-3" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                  <div class="form-group">
                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="text-danger small mt-1">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="New Password" required autocomplete="new-password">
                    @error('password')
                        <span class="text-danger small mt-1">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm Password" required autocomplete="new-password">
                  </div>

                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">RESET PASSWORD</button>
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