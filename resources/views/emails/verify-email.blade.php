<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #e31e24;
            padding: 40px;
            text-align: center;
        }
        .content {
            padding: 40px;
        }
        .footer {
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            background-color: #fcfcfc;
        }
        .button {
            display: inline-block;
            padding: 16px 32px;
            background-color: #e31e24;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            margin-top: 25px;
            box-shadow: 0 4px 6px -1px rgba(227, 30, 36, 0.2);
        }
        h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        p {
            margin: 16px 0;
            font-size: 16px;
            color: #4b5563;
        }
        .welcome-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #fef2f2;
            color: #e31e24;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #ffffff;">PORTAL GAWE</h1>
            <p style="color: rgba(255,255,255,0.8); margin: 0; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em;">Karang Taruna Desa Campaka</p>
        </div>
        <div class="content">
            <div class="welcome-badge">Selamat Datang!</div>
            <h1>Halo, {{ $name }}!</h1>
            <p>Terima kasih telah bergabung dengan <strong>Portalgawe</strong>, portal lowongan kerja resmi Karang Taruna Desa Campaka.</p>
            <p>Satu langkah lagi untuk memulai perjalanan karirmu. Silakan konfirmasi alamat emailmu dengan menekan tombol di bawah ini:</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Konfirmasi Email Saya</a>
            </div>

            <p style="margin-top: 30px; font-size: 14px;">Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin tautan berikut ke browser Anda:</p>
            <p style="font-size: 12px; color: #9ca3af; word-break: break-all;">{{ $url }}</p>
        </div>
        <div class="footer">
            <p style="margin: 0;">&copy; {{ date('Y') }} Karang Taruna Desa Campaka. Seluruh hak cipta dilindungi.</p>
            <p style="margin-top: 8px;">Kecamatan Campaka, Kabupaten Purwakarta, Jawa Barat.</p>
        </div>
    </div>
</body>
</html>
