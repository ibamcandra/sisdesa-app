<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; }
        .footer { text-align: center; font-size: 12px; color: #999; }
        .button { display: inline-block; padding: 12px 24px; background-color: #ef4444; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .detail-box { background-color: #f9fafb; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #ef4444; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #ef4444;">Undangan Interview</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $application->applicantProfile->name }}</strong>,</p>
            <p>Selamat! Lamaran Anda untuk posisi <strong>{{ $application->vacancy->title }}</strong> telah terpilih untuk tahap selanjutnya, yaitu <strong>Interview</strong>.</p>
            
            <p>Kami mengundang Anda untuk hadir pada sesi interview yang telah dijadwalkan sebagai berikut:</p>
            
            <div class="detail-box">
                <p style="margin: 5px 0;"><strong>📅 Tanggal:</strong> {{ \Carbon\Carbon::parse($interviewData['interview_date'])->format('d M Y') }}</p>
                <p style="margin: 5px 0;"><strong>⏰ Jam:</strong> {{ $interviewData['interview_time'] }}</p>
                <p style="margin: 5px 0;"><strong>📍 Tempat/Link:</strong> {{ $interviewData['interview_location'] }}</p>
            </div>
            
            <p>Mohon konfirmasi kehadiran Anda dengan membalas email ini atau melalui kontak WhatsApp kami.</p>
            <p>Sampai jumpa dan semoga sukses!</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Karang Taruna Desa Campaka. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
