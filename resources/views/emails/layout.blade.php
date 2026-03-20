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
            background-color: #142472;
            padding: 40px 20px;
            text-align: center;
        }
        .header img {
            max-width: 200px;
        }
        .content {
            padding: 40px;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        .button {
            display: inline-block;
            padding: 12px 32px;
            background-color: #D3FF8A;
            color: #142472;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 20px;
        }
        .highlight {
            color: #142472;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ url('/') }}" style="text-decoration: none;">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                    <tr>
                        <td style="background-color: #D3FF8A; border-radius: 8px; width: 44px; height: 44px; text-align: center; vertical-align: middle;">
                            <span style="color: #142472; font-size: 20px;">&#10148;</span>
                        </td>
                        <td style="padding-left: 12px; vertical-align: middle;">
                            <span style="color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; font-family: 'Inter', sans-serif;">
                                MOD<span style="background-color: #D3FF8A; color: #142472; padding: 2px 8px; margin-left: 4px; border-radius: 4px;">REMIT</span>
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
