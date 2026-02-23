<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin - Login</title>
    @include('layouts.style-global')
    <style>
      /* Style Tombol Google Minimalis */
      .btn-google-minimalist {
        background-color: #ffffff;
        color: #333333;
        border: 1px solid #dadce0;
        border-radius: 25px; /* Pill shape */
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background-color .3s;
        font-weight: 500;
        margin-top: 15px;
      }
      .btn-google-minimalist:hover {
        background-color: #f8f9fa;
        color: #333333;
        border-color: #d2e3fc;
      }
      .google-logo {
        width: 18px;
        height: 18px;
        margin-right: 10px;
      }
      .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
        color: #bbb;
      }
      .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
      }
      .divider:not(:empty)::before { margin-right: .5em; }
      .divider:not(:empty)::after { margin-left: .5em; }
    </style>
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
                <h4>Hello! let's get started</h4>
                <h6 class="font-weight-light">Sign in to continue.</h6>
                
                <form class="pt-3" method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="form-group">
                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Username" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>

                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                  </div>

                  <div class="divider">OR</div>

                  <!-- Tombol Login Google Minimalis -->
                  <div class="d-grid gap-2">
                    <a href="{{ route('google.login') }}" class="btn-google-minimalist">
                      <svg class="google-logo" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z" fill="#EA4335"/>
                      </svg>
                      Continue with Google
                    </a>
                  </div>

                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Keep me signed in </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-link text-primary">Forgot password?</a>
                    @endif
                  </div>

                  <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="{{ route('register') }}" class="text-primary">Create</a>
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