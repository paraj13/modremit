<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #142472;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 2px solid #ffde42;
        }
        .header img {
            max-width: 200px;
        }
        .content {
            padding: 40px;
        }
        .footer {
            background-color: #ffde42;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #142472;
        }
        .button {
            display: inline-block;
            padding: 14px 40px;
            background-color: #ffde42;
            color: #142472 !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 800;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .button-dark {
            background-color: #142472;
            color: #ffde42 !important;
        }
        .alert {
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid transparent;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .alert-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .text-center { text-align: center; }
        .text-muted { color: #64748b; font-size: 14px; }
        .mt-0 { margin-top: 0; }
        .mt-30 { margin-top: 30px; }
        .mb-0 { margin-bottom: 0; }
        .fw-bold { font-weight: bold; }
        .fs-14 { font-size: 14px; }
        .highlight {
            color: #142472;
            font-weight: bold;
        }
        .italic { font-style: italic; }
        .mt-5 { margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header" style="background-color:#142472>
            <a href="{{ url('/') }}" style="text-decoration: none;">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                    <tr>
                        <td style="background-color: #ffde42; border-radius: 6px; width: 36px; height: 36px; text-align: center; vertical-align: middle;">
                            <span style="color: #142472; font-size: 18px;"></span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: middle;">
                            <span style="color: #ffde42; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; font-family: 'Inter', sans-serif; text-transform: uppercase;">
                                MOD<span style="background-color: #ffde42; color: #142472; padding: 2px 8px; margin-left: 4px; border-radius: 4px;">REMIT</span>
                            </span>
                        </td>
                    </tr>
                </table>
            </a>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Modremit. All rights reserved.<br>
            Secure, Fast, and Reliable Money Transfers.
        </div>
    </div>
</body>
</html>
