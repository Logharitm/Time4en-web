<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset</title>

  <style>
    body {
      margin: 0 !important;
      padding: 0 !important;
      font-family: 'Tahoma', 'Cairo', sans-serif !important;
      background-color: #f9f9f9 !important;
      color: #333 !important;
      text-align: center !important;
      direction: ltr !important;
    }

    .container {
      width: 100% !important;
      padding: 40px 0 !important;
      background: linear-gradient(180deg, #c0496b, #451291) !important;
    }

    .card {
      background: #fff !important;
      border-radius: 16px !important;
      max-width: 480px !important;
      margin: 0 auto !important;
      padding: 30px 25px !important;
      box-shadow: 0 6px 15px rgba(0,0,0,0.15) !important;
    }

    .logo {
      width: 120px !important;
      margin-bottom: 20px !important;
    }

    h1 {
      color: #451291 !important;
      font-size: 24px !important;
      margin-bottom: 10px !important;
    }

    p {
      font-size: 15px !important;
      line-height: 1.8 !important;
      color: #555 !important;
      margin: 10px 0 !important;
    }

    .divider {
      border-top: 1px solid rgba(69, 18, 145, 0.2) !important;
      margin: 25px auto !important;
      width: 80% !important;
    }

    .btn {
      display: inline-block !important;
      padding: 12px 30px !important;
      border-radius: 8px !important;
      background-color: #451291 !important;
      color: #fff !important;
      text-decoration: none !important;
      font-size: 16px !important;
      margin-top: 15px !important;
      font-weight: bold !important;
    }

    .btn:hover {
      background-color: #5b19b9 !important;
    }

    .footer {
      font-size: 12px !important;
      color: #999 !important;
      margin-top: 25px !important;
    }

    @media (max-width: 600px) {
      .card {
        width: 90% !important;
        padding: 20px !important;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card">
      <h1>Password Reset</h1>
      <img src="https://time4en-site.logharitm.com/_nuxt/logo_ltr.Byx1Cvct.png" alt="Website Logo" class="logo">
      <h1>Forgot Your Password?</h1>

      <p>We received a request to reset your password.</p>
      <p>If you did not make this request, you can safely ignore this email.</p>

      <div class="divider"></div>

      <p>If you requested this password reset, simply click the button below:</p>

      <a href="{{ $resetUrl }}" class="btn" target="_blank">Reset Password</a>

      <div class="footer">
        <p>If you didn’t request a password change, no further action is required. It’s that simple.</p>
      </div>
    </div>
  </div>
</body>
</html>
