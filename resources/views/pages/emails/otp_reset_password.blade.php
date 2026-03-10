<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>OTP Reset Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:480px; background:#ffffff; border-radius:10px; padding:28px; box-shadow:0 4px 10px rgba(0,0,0,0.08);">

                    <!-- GREETING -->
                    <tr>
                        <td style="font-size:14px; color:#111827;">
                            Halo <strong>{{ $name }}</strong>,
                        </td>
                    </tr>

                    <!-- MESSAGE -->
                    <tr>
                        <td style="padding-top:12px; font-size:14px; color:#374151; line-height:1.6;">
                            Gunakan kode OTP berikut untuk mereset password Anda:
                        </td>
                    </tr>

                    <!-- OTP CODE -->
                    <tr>
                        <td align="center" style="padding:24px 0;">
                            <div
                                style="
                                font-size:32px;
                                font-weight:bold;
                                letter-spacing:6px;
                                color:#111827;
                                background:#f9fafb;
                                padding:16px 32px;
                                border:2px dashed #AA2224;
                                border-radius:8px;
                                display:inline-block;
                            ">
                                {{ $otp }}
                            </div>
                        </td>
                    </tr>

                    <!-- INFO -->
                    <tr>
                        <td style="font-size:13px; color:#6b7280; line-height:1.6;">
                            Jangan bagikan kode ini kepada siapa pun.<br>
                            Kode OTP berlaku selama <strong>10 menit</strong>.
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding-top:24px; font-size:12px; color:#9ca3af;">
                            Terima kasih,<br>
                            <strong>SP PION</strong>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
