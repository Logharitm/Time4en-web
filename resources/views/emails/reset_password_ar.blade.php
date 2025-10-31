<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>

    <style>
        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Tahoma', 'Cairo', sans-serif !important;
            background-color: #f9f9f9 !important;
            color: #333 !important;
            text-align: center !important;
            direction: rtl !important;
        }

        .container {
            max-width: 600px !important;
            padding: 40px 0 !important;
            direction: rtl !important;
            text-align: center !important;
            background: linear-gradient(180deg, #c0496b, #451291) !important;
        }

        .card {
            background: #fff !important;
            border-radius: 16px !important;
            max-width: 480px !important;
            margin: 0 auto !important;
            padding: 30px 25px !important;
            direction: rtl !important;
            text-align: center !important;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15) !important;
        }

        .logo {
            width: 120px !important;
            margin-bottom: 20px !important;
            text-align: center !important;
        }

        h1 {
            color: #451291 !important;
            font-size: 24px !important;
            margin-bottom: 10px !important;
            text-align: center !important;
        }

        p {
            font-size: 15px !important;
            line-height: 1.8 !important;
            color: #555 !important;
            margin: 10px 0 !important;
            text-align: center !important;
        }

        .divider {
            border-top: 1px solid rgba(69, 18, 145, 0.2) !important;
            margin: 25px auto !important;
            width: 80% !important;
            text-align: center !important;
        }

        .btn {
            display: inline-block !important;
            padding: 12px 30px !important;
            border-radius: 8px !important;
            background-color: #451291 !important;
            color: #fff !important !important;
            text-decoration: none !important;
            font-size: 16px !important;
            margin-top: 15px !important;
            font-weight: bold !important;
            text-align: center !important;
        }

        .btn:hover {
            background-color: #5b19b9 !important;
        }

        .footer {
            font-size: 12px !important;
            color: #999 !important;
            margin-top: 25px !important;
            text-align: center !important;
        }

        @media (max-width: 600px) {
            .card {
                width: 90% !important;
                padding: 20px !important;
                text-align: center !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>إعادة تعيين كلمة المرور</h1>
            <img src="https://time4en-site.logharitm.com/_nuxt/logo_rtl.CN1rsYND.png" alt="شعار الموقع" class="logo">
            <h1>هل نسيت كلمة المرور؟</h1>

            <p>لقد تلقينا طلبًا لإعادة تعيين كلمة المرور الخاصة بك.</p>
            <p>إذا لم تقم بتقديم هذا الطلب، فما عليك سوى تجاهل هذا البريد الإلكتروني.</p>

            <div class="divider"></div>

            <p>إذا كنت من قام بتقديم هذا الطلب، ما عليك سوى الضغط على الزر أدناه:</p>

            <a href="{{ $resetUrl }}" class="btn" target="_blank">إعادة تعيين كلمة المرور</a>

            <div class="footer">
                <p>إذا لم تطلب تغيير كلمة المرور الخاصة بك،
                    <br>
                    فلست بحاجة إلى القيام بأي شيء.
                    <br>
                    الأمر بهذه السهولة.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
