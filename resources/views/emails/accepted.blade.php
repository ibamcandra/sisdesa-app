<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; }
        .footer { text-align: center; font-size: 12px; color: #999; }
        .success-box { background-color: #f0fdf4; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #22c55e; color: #166534; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #22c55e;">Selamat! Anda Diterima</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $application->applicantProfile->name }}</strong>,</p>
            
            <div class="success-box">
                <p>Kami sangat senang menginformasikan bahwa Anda dinyatakan <strong>DITERIMA</strong> untuk bergabung bersama kami pada posisi <strong>{{ $application->vacancy->title }}</strong>.</p>
            </div>
            
            <p>Tim HR kami akan segera menghubungi Anda kembali melalui WhatsApp atau Email untuk membicarakan langkah selanjutnya terkait penawaran kerja (*Offering Letter*) dan dokumen yang diperlukan.</p>
            
            <p>Selamat bergabung di keluarga besar Karang Taruna Desa Campaka!</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Karang Taruna Desa Campaka. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
