<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin - Verify Email</title>
    @include('layouts.style-global')
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-6 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo text-center">
                  <img src="{{ asset('assets/images/logo.svg') }}">
                </div>
                <h4 class="text-center">Verify Your Email Address</h4>
                
                @if (session('resent'))
                    <div class="alert alert-success mt-3" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <p class="font-weight-light mt-3 text-center">
                    Before proceeding, please check your email for a verification link.
                    If you did not receive the email,
                </p>
                
                <form class="d-inline text-center d-block" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <div class="mt-3 d-grid gap-2">
                        <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">CLICK HERE TO REQUEST ANOTHER</button>
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