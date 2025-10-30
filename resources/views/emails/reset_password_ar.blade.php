<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إعادة تعيين كلمة المرور</title>

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Tahoma', 'Cairo', sans-serif;
      background-color: #f9f9f9;
      color: #333;
      text-align: center;
      direction: rtl;
    }

    .container {
      width: 100%;
      padding: 40px 0;
      background: linear-gradient(180deg, #c0496b, #451291);
    }

    .card {
      background: #fff;
      border-radius: 16px;
      max-width: 480px;
      margin: 0 auto;
      padding: 30px 25px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .logo {
      width: 120px;
      margin-bottom: 20px;
    }

    h1 {
      color: #451291;
      font-size: 24px;
      margin-bottom: 10px;
    }

    p {
      font-size: 15px;
      line-height: 1.8;
      color: #555;
      margin: 10px 0;
    }

    .divider {
      border-top: 1px solid rgba(69, 18, 145, 0.2);
      margin: 25px auto;
      width: 80%;
    }

    .btn {
      display: inline-block;
      padding: 12px 30px;
      border-radius: 8px;
      background-color: #451291;
      color: #fff !important;
      text-decoration: none;
      font-size: 16px;
      margin-top: 15px;
      font-weight: bold;
    }

    .btn:hover {
      background-color: #5b19b9;
    }

    .footer {
      font-size: 12px;
      color: #999;
      margin-top: 25px;
    }

    @media (max-width: 600px) {
      .card {
        width: 90%;
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card">
      <img src="https://time4en.logharitm.com/assets/Logo-DteKpv2F.png" alt="شعار الموقع" class="logo">

      <h1>هل نسيت كلمة المرور؟</h1>

      <p>لقد تلقينا طلبًا لإعادة تعيين كلمة المرور الخاصة بك.</p>
      <p>إذا لم تقم بتقديم هذا الطلب، فما عليك سوى تجاهل هذا البريد الإلكتروني.</p>

      <div class="divider"></div>

      <p>إذا كنت من قام بتقديم هذا الطلب، ما عليك سوى الضغط على الزر أدناه:</p>

      <a href="{{ $resetUrl }}" class="btn" target="_blank">إعادة تعيين كلمة المرور</a>

      <div class="footer">
        <p>إذا لم تطلب تغيير كلمة المرور الخاصة بك، فلست بحاجة إلى القيام بأي شيء. الأمر بهذه السهولة.</p>
      </div>
    </div>
  </div>
</body>
</html>
