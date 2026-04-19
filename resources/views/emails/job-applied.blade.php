<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #e11d48; color: #fff !important; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #e11d48;">Lamaran Terkirim!</h1>
        </div>
        
        <p>Halo, <strong>{{ $application->applicantProfile->name }}</strong>,</p>
        
        <p>Terima kasih telah melamar untuk posisi <strong>{{ $application->vacancy->title }}</strong> di <strong>{{ $application->vacancy->branch->name ?? 'Karang Taruna Desa Campaka' }}</strong>.</p>
        
        <p>Lamaran Anda telah kami terima dan saat ini berstatus <strong>"Terkirim"</strong>. Tim kami akan segera meninjau kualifikasi Anda.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/history') }}" class="btn">Pantau Status Lamaran</a>
        </div>
        
        <p>Kami akan memberikan informasi lebih lanjut melalui email atau WhatsApp jika Anda terpilih untuk tahap berikutnya.</p>
        
        <p>Salam hangat,<br><strong>Karang Taruna Desa Campaka</strong></p>
        
        <div class="footer">
            <p>Ini adalah email otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
