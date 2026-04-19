<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; }
        .footer { text-align: center; font-size: 12px; color: #999; }
        .info-box { background-color: #f9fafb; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #4b5563;">Update Status Lamaran</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $application->applicantProfile->name }}</strong>,</p>
            
            <p>Terima kasih telah melamar posisi <strong>{{ $application->vacancy->title }}</strong> di Karang Taruna Desa Campaka dan telah meluangkan waktu untuk mengikuti proses seleksi kami.</p>
            
            <div class="info-box">
                <p>Kami ingin menginformasikan bahwa saat ini kami belum dapat melanjutkan lamaran Anda ke tahap berikutnya.</p>
            </div>
            
            <p>Keputusan ini tidak mengurangi penghargaan kami terhadap keahlian dan kualifikasi yang Anda miliki. Kami akan menyimpan profil Anda dalam database kami untuk peluang di masa mendatang yang mungkin sesuai dengan latar belakang Anda.</p>
            
            <p>Tetap semangat dan kami mendoakan kesuksesan untuk perjalanan karir Anda.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Karang Taruna Desa Campaka. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
