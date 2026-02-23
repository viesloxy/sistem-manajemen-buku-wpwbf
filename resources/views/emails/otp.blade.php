<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #9a55ff;">Verifikasi Keamanan</h2>
        <p>Halo,</p>
        <p>Kami menerima permintaan login ke akun Anda melalui Google. Gunakan kode OTP di bawah ini untuk melanjutkan:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; background: #f3f3f3; padding: 10px 20px; border-radius: 5px; border: 1px dashed #9a55ff;">
                {{ $otp }}
            </span>
        </div>

        <p>Kode ini berlaku selama 5 menit. Jangan memberikan kode ini kepada siapapun termasuk pihak yang mengaku sebagai admin.</p>
        <hr style="border: 0; border-top: 1px solid #eee;">
        <p style="font-size: 12px; color: #777;">Ini adalah email otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>
</html>