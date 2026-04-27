<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.6; color: #1a1a1a; margin: 0; padding: 0; background-color: #f9fafb; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .header { background-color: #e31e24; padding: 40px; text-align: center; color: white; }
        .content { padding: 40px; }
        .credentials { background-color: #f3f4f6; padding: 20px; border-radius: 12px; margin: 20px 0; }
        .button { display: inline-block; padding: 16px 32px; background-color: #e31e24; color: #ffffff !important; text-decoration: none; border-radius: 12px; font-weight: bold; margin-top: 25px; }
        .footer { padding: 30px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">PORTAL GAWE</h1>
            <p style="margin:0; opacity:0.8; font-size:12px;">Sistem Informasi Lowongan Kerja</p>
        </div>
        <div class="content">
            <h2>Halo, {{ $name }}!</h2>
            <p>Selamat! Akun Anda telah berhasil didaftarkan di <strong>Sistem Informasi Lowongan Kerja Karang Taruna Desa Campaka</strong>.</p>
            
            @if($role === 'recruitment' || $role === 'super_admin')
                <p>Sebagai Admin/Rekruter, Anda kini dapat mencari kandidat potensial dari data warga kami dan mulai memposting lowongan pekerjaan untuk memajukan desa.</p>
            @else
                <p>Anda kini dapat mulai melengkapi profil dan mencari pekerjaan impian Anda di portal kami.</p>
            @endif

            <div class="credentials">
                <p style="margin:0; font-size:14px; color:#6b7280;">Detail Login Anda:</p>
                <p style="margin:5px 0;"><strong>Email:</strong> {{ $email }}</p>
                <p style="margin:5px 0;"><strong>Password:</strong> {{ $password }}</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/admin" class="button">Masuk ke Dashboard</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Karang Taruna Desa Campaka. <br> Terima kasih.</p>
        </div>
    </div>
</body>
</html>
