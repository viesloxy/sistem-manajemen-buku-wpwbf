<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin - Verify OTP</title>
    @include('layouts.style-global')
    <style>
        .otp-boxes {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        .otp-input-field {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            border: 2px solid #ebedf2;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .otp-input-field:focus {
            border-color: #9a55ff;
            outline: none;
            background: #fff;
        }
        .icon-box {
            font-size: 50px;
            color: #9a55ff;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-center p-5">
                            <div class="brand-logo">
                                <img src="{{ asset('assets/images/logo.svg') }}">
                            </div>
                            <div class="icon-box"><i class="mdi mdi-shield-check-outline"></i></div>
                            <h4>Account Verification</h4>
                            <p class="text-muted">Masukkan 6 digit kode yang dikirim ke email Anda.</p>
                            
                            <form id="otp-form" method="POST" action="{{ route('otp.verify') }}">
                                @csrf
                                <!-- Hidden input untuk menampung gabungan 6 digit -->
                                <input type="hidden" name="otp" id="final-otp">
                                
                                <div class="otp-boxes">
                                    <input type="text" class="otp-input-field" maxlength="1" pattern="\d*" inputmode="numeric" autofocus>
                                    <input type="text" class="otp-input-field" maxlength="1" pattern="\d*" inputmode="numeric">
                                    <input type="text" class="otp-input-field" maxlength="1" pattern="\d*" inputmode="numeric">
                                    <input type="text" class="otp-input-field" maxlength="1" pattern="\d*" inputmode="numeric">
                                    <input type="text" class="otp-input-field" maxlength="1" pattern="\d*" inputmode="numeric">
                                    <input type="text" class="otp-input-field" maxlength="1" pattern="\d*" inputmode="numeric">
                                </div>

                                @error('otp')
                                    <p class="text-danger"><strong>{{ $message }}</strong></p>
                                @enderror

                                <div class="mt-3 d-grid gap-2">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">VERIFIKASI</button>
                                </div>
                                
                                <div class="text-center mt-4 font-weight-light"> 
                                    Tidak menerima kode? <a href="#" class="text-primary">Kirim ulang</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.javascript-global')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input-field');
            const hiddenOtp = document.getElementById('final-otp');
            const form = document.getElementById('otp-form');

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });

            form.addEventListener('submit', function() {
                let combined = "";
                inputs.forEach(input => combined += input.value);
                hiddenOtp.value = combined;
            });
        });
    </script>
</body>
</html>