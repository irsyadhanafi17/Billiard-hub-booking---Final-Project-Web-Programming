<?php
$autoloadPath = dirname(__FILE__) . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    
    class Mail {
        public static function SendMail($to, $name, $subject, $message) {
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: Afterhour <noreply@afterhour.id>\r\n";
            return mail($to, $subject, $message, $headers);
        }
        public static function BroadcastDiscount($users, $discount) {
            $success = 0;
            foreach ($users as $user) {
                $body = self::_buildDiscountBody($user->name, $discount);
                if (self::SendMail($user->email, $user->name, '🎱 Promo Spesial dari Afterhour!', $body)) $success++;
            }
            return $success;
        }
        public static function SendBookingConfirmation($user_email, $user_name, $booking_data) {
            $body = self::_buildBookingBody($user_name, $booking_data);
            return self::SendMail($user_email, $user_name, '✅ Konfirmasi Reservasi Afterhour', $body);
        }
        public static function SendWelcome($email, $name) {
            $body = self::_buildWelcomeBody($name);
            return self::SendMail($email, $name, '🎱 Selamat Bergabung di Afterhour!', $body);
        }
        private static function _buildDiscountBody($name, $discount) {
            return "
            <div style='font-family:Arial,sans-serif;background:#0D0D0D;color:#fff;padding:30px;border-radius:12px;max-width:600px;margin:auto'>
              <div style='text-align:center;margin-bottom:24px'>
                <h1 style='color:#8bd100;margin:0;font-size:32px'>AFTERHOUR</h1>
                <p style='color:#666;font-size:12px;margin:4px 0 0'>Billiard &amp; Lounge Premium</p>
              </div>
              <div style='background:#1a0a0e;border:1px solid #2f0618;border-radius:12px;padding:24px;margin-bottom:20px'>
                <p style='margin:0 0 8px'>Halo <strong style='color:#8bd100'>{$name}</strong>! 🎉</p>
                <p style='margin:0 0 16px;color:#ccc'>Ada promo spesial untukmu dari <strong>Afterhour</strong>:</p>
                <div style='background:#2f0618;border-radius:8px;padding:20px;text-align:center;margin-bottom:16px'>
                  <h2 style='color:#8bd100;margin:0 0 8px;font-size:22px'>{$discount->title}</h2>
                  <div style='font-size:48px;font-weight:bold;color:#fff;margin:8px 0'>{$discount->discount_pct}%</div>
                  <p style='color:#ccc;margin:0;font-size:14px'>DISKON SPESIAL</p>
                </div>
                <p style='color:#ccc;margin:0 0 12px'>{$discount->description}</p>
                <p style='color:#888;font-size:13px;margin:0'>Berlaku: <strong style='color:#fff'>" . date('d M Y',strtotime($discount->valid_from)) . " – " . date('d M Y',strtotime($discount->valid_until)) . "</strong></p>
              </div>
              <div style='text-align:center;margin-top:24px'>
                <a href='#' style='background:#8bd100;color:#000;padding:12px 32px;border-radius:30px;text-decoration:none;font-weight:bold;font-size:14px'>BOOKING SEKARANG</a>
              </div>
              <p style='text-align:center;color:#444;font-size:12px;margin-top:24px'>© Afterhour Billiard &amp; Lounge. All rights reserved.</p>
            </div>";
        }
        private static function _buildBookingBody($name, $data) {
            return "
            <div style='font-family:Arial,sans-serif;background:#0D0D0D;color:#fff;padding:30px;border-radius:12px;max-width:600px;margin:auto'>
              <h1 style='color:#8bd100;text-align:center'>AFTERHOUR</h1>
              <h2 style='text-align:center'>✅ Booking Dikonfirmasi!</h2>
              <p>Halo <strong>{$name}</strong>, reservasi Anda telah berhasil!</p>
              <table style='width:100%;border-collapse:collapse;background:#1a0a0e;border-radius:8px;overflow:hidden'>
                <tr><td style='padding:12px;border-bottom:1px solid #2f0618;color:#888'>Outlet</td><td style='padding:12px;border-bottom:1px solid #2f0618'>{$data['outlet_name']}</td></tr>
                <tr><td style='padding:12px;border-bottom:1px solid #2f0618;color:#888'>Meja</td><td style='padding:12px;border-bottom:1px solid #2f0618'>No. {$data['table_number']} ({$data['class_type']})</td></tr>
                <tr><td style='padding:12px;border-bottom:1px solid #2f0618;color:#888'>Tanggal</td><td style='padding:12px;border-bottom:1px solid #2f0618'>" . date('d M Y',strtotime($data['booking_date'])) . "</td></tr>
                <tr><td style='padding:12px;border-bottom:1px solid #2f0618;color:#888'>Jam Mulai</td><td style='padding:12px;border-bottom:1px solid #2f0618'>{$data['start_time']}</td></tr>
                <tr><td style='padding:12px;border-bottom:1px solid #2f0618;color:#888'>Durasi</td><td style='padding:12px;border-bottom:1px solid #2f0618'>{$data['duration_hours']} Jam</td></tr>
                <tr><td style='padding:12px;color:#888'>Total Bayar</td><td style='padding:12px;color:#8bd100;font-weight:bold'>Rp " . number_format($data['total_price'],0,',','.') . "</td></tr>
              </table>
              <p style='color:#888;font-size:13px;margin-top:16px'>Tunjukkan email ini ke kasir atau lakukan transfer ke BCA 123-456-7890 a.n Afterhour Group.</p>
            </div>";
        }
        private static function _buildWelcomeBody($name) {
            return "
            <div style='font-family:Arial,sans-serif;background:#0D0D0D;color:#fff;padding:30px;border-radius:12px;max-width:600px;margin:auto'>
              <h1 style='color:#8bd100;text-align:center'>AFTERHOUR</h1>
              <h2 style='text-align:center'>Selamat Bergabung, {$name}! 🎱</h2>
              <p>Akun Afterhour Member Anda telah aktif. Nikmati berbagai keuntungan eksklusif dan booking meja premium kapan saja!</p>
              <div style='background:#1a0a0e;border:1px solid #2f0618;border-radius:8px;padding:20px;margin:20px 0'>
                <h3 style='color:#8bd100;margin:0 0 12px'>Keuntungan Member:</h3>
                <ul style='color:#ccc;padding-left:20px;margin:0'>
                  <li>Akses booking meja Regular, VIP, &amp; VVIP</li>
                  <li>Diskon eksklusif di semua outlet</li>
                  <li>Notifikasi promo langsung ke email</li>
                </ul>
              </div>
              <p style='text-align:center;margin-top:24px'>© Afterhour Billiard &amp; Lounge</p>
            </div>";
        }
    }
    return; 
}

require_once $autoloadPath;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    const SMTP_USERNAME  = 'ontaxp@gmail.com';      
    const SMTP_PASSWORD  = 'azrh ocpo oxuo jhma';     
    const SMTP_FROM      = 'ontaxp@gmail.com';        
    const SMTP_FROM_NAME = 'Afterhour Billiard & Lounge'; 

    public static function SendMail($to, $name, $subject, $message)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = 587;
            $mail->Username   = self::SMTP_USERNAME;
            $mail->Password   = self::SMTP_PASSWORD;
            $mail->From       = self::SMTP_FROM;
            $mail->FromName   = self::SMTP_FROM_NAME;
            $mail->SMTPOptions = [
                'ssl' => ['verify_peer'=>false,'verify_peer_name'=>false,'allow_self_signed'=>true]
            ];
            $mail->WordWrap = 50;
            $mail->isHTML(true);
            $mail->addAddress($to, $name);
            $mail->Subject  = $subject;
            $mail->Body     = $message;
            $mail->AltBody  = strip_tags($message);
            $mail->SMTPDebug = 0;
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Afterhour Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Broadcast diskon ke banyak user
     * @param  array $users     array of User objects (harus punya ->email dan ->name)
     * @param  object $discount Discount object
     * @return int jumlah email berhasil terkirim
     */
    public static function BroadcastDiscount($users, $discount)
    {
        $success = 0;
        foreach ($users as $user) {
            $body = self::_buildDiscountBody($user->name, $discount);
            if (self::SendMail($user->email, $user->name, '🎱 Promo Spesial dari Afterhour!', $body)) {
                $success++;
            }
        }
        return $success;
    }

    public static function SendBookingConfirmation($user_email, $user_name, $booking_data)
    {
        $body = self::_buildBookingBody($user_name, $booking_data);
        return self::SendMail($user_email, $user_name, '✅ Konfirmasi Reservasi Afterhour', $body);
    }

    public static function SendWelcome($email, $name)
    {
        $body = self::_buildWelcomeBody($name);
        return self::SendMail($email, $name, '🎱 Selamat Bergabung di Afterhour!', $body);
    }


    private static function _buildDiscountBody($name, $discount)
    {
        $from  = date('d M Y', strtotime($discount->valid_from));
        $until = date('d M Y', strtotime($discount->valid_until));
        return "
<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:20px;background:#111'>
<div style='font-family:Arial,sans-serif;background:#0D0D0D;color:#fff;padding:40px;border-radius:16px;max-width:600px;margin:auto;border:1px solid #2f0618'>
  <div style='text-align:center;margin-bottom:30px'>
    <h1 style='color:#8bd100;margin:0;font-size:34px;letter-spacing:2px'>AFTERHOUR</h1>
    <p style='color:#555;font-size:11px;margin:4px 0 0;letter-spacing:3px'>BILLIARD &amp; LOUNGE PREMIUM</p>
  </div>
  <div style='border-top:1px solid #2f0618;border-bottom:1px solid #2f0618;padding:24px 0;margin-bottom:24px;text-align:center'>
    <p style='color:#888;margin:0 0 6px;font-size:13px'>PROMO EKSKLUSIF UNTUK</p>
    <h2 style='color:#fff;margin:0;font-size:26px'>{$name} 🎉</h2>
  </div>
  <div style='background:#1a0a0e;border:1px solid #2f0618;border-radius:12px;padding:30px;text-align:center;margin-bottom:24px'>
    <p style='color:#8bd100;font-size:12px;letter-spacing:3px;margin:0 0 8px'>DISKON SPESIAL</p>
    <h2 style='color:#fff;font-size:24px;margin:0 0 10px'>{$discount->title}</h2>
    <div style='font-size:64px;font-weight:900;color:#8bd100;line-height:1;margin:12px 0'>{$discount->discount_pct}%</div>
    <p style='color:#aaa;font-size:14px;line-height:1.6;margin:12px 0'>{$discount->description}</p>
    <p style='color:#666;font-size:12px;margin:16px 0 0'>Berlaku: <strong style='color:#fff'>{$from} – {$until}</strong></p>
  </div>
  <div style='text-align:center;margin-bottom:30px'>
    <a href='#' style='display:inline-block;background:#8bd100;color:#000;padding:14px 40px;border-radius:30px;text-decoration:none;font-weight:bold;font-size:15px;letter-spacing:1px'>BOOKING SEKARANG</a>
  </div>
  <p style='text-align:center;color:#333;font-size:11px;margin:0'>© Afterhour Billiard &amp; Lounge &bull; All rights reserved</p>
</div>
</body>
</html>";
    }

    private static function _buildBookingBody($name, $data)
    {
        $date = date('d M Y', strtotime($data['booking_date']));
        $price = 'Rp ' . number_format($data['total_price'],0,',','.');
        return "
<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:20px;background:#111'>
<div style='font-family:Arial,sans-serif;background:#0D0D0D;color:#fff;padding:40px;border-radius:16px;max-width:600px;margin:auto;border:1px solid #2f0618'>
  <div style='text-align:center;margin-bottom:24px'>
    <h1 style='color:#8bd100;margin:0;font-size:34px'>AFTERHOUR</h1>
    <p style='color:#555;font-size:11px;margin:4px 0 0;letter-spacing:3px'>KONFIRMASI RESERVASI</p>
  </div>
  <h2 style='text-align:center;color:#fff;font-size:22px'>✅ Booking Berhasil!</h2>
  <p style='color:#ccc;text-align:center'>Halo <strong style='color:#8bd100'>{$name}</strong>, reservasi meja Anda telah terkonfirmasi.</p>
  <table style='width:100%;border-collapse:collapse;background:#1a0a0e;border-radius:8px;overflow:hidden;margin:20px 0'>
    <tr><td style='padding:14px 18px;border-bottom:1px solid #2f0618;color:#666;width:40%'>Outlet</td><td style='padding:14px 18px;border-bottom:1px solid #2f0618;font-weight:bold'>{$data['outlet_name']}</td></tr>
    <tr><td style='padding:14px 18px;border-bottom:1px solid #2f0618;color:#666'>Nomor Meja</td><td style='padding:14px 18px;border-bottom:1px solid #2f0618'>Meja {$data['table_number']} ({$data['class_type']})</td></tr>
    <tr><td style='padding:14px 18px;border-bottom:1px solid #2f0618;color:#666'>Tanggal</td><td style='padding:14px 18px;border-bottom:1px solid #2f0618'>{$date}</td></tr>
    <tr><td style='padding:14px 18px;border-bottom:1px solid #2f0618;color:#666'>Jam Mulai</td><td style='padding:14px 18px;border-bottom:1px solid #2f0618'>{$data['start_time']}</td></tr>
    <tr><td style='padding:14px 18px;border-bottom:1px solid #2f0618;color:#666'>Durasi</td><td style='padding:14px 18px;border-bottom:1px solid #2f0618'>{$data['duration_hours']} Jam</td></tr>
    <tr><td style='padding:14px 18px;color:#666'>Total Tagihan</td><td style='padding:14px 18px;color:#8bd100;font-size:20px;font-weight:900'>{$price}</td></tr>
  </table>
  <div style='background:#1a0a0e;border:1px solid #2f0618;border-radius:8px;padding:18px;margin-bottom:20px'>
    <h4 style='color:#8bd100;margin:0 0 10px;font-size:14px'>📋 CARA PEMBAYARAN:</h4>
    <p style='color:#aaa;font-size:13px;margin:0 0 8px'><strong style='color:#fff'>Metode 1:</strong> Tunjukkan email ini ke kasir outlet saat kedatangan.</p>
    <p style='color:#aaa;font-size:13px;margin:0'><strong style='color:#fff'>Metode 2:</strong> Transfer ke BCA 123-456-7890 a.n <em>Afterhour Group</em>, lalu konfirmasi ke WhatsApp Admin.</p>
  </div>
  <p style='text-align:center;color:#333;font-size:11px;margin:0'>© Afterhour Billiard &amp; Lounge</p>
</div>
</body>
</html>";
    }

    private static function _buildWelcomeBody($name)
    {
        return "
<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:20px;background:#111'>
<div style='font-family:Arial,sans-serif;background:#0D0D0D;color:#fff;padding:40px;border-radius:16px;max-width:600px;margin:auto;border:1px solid #2f0618'>
  <div style='text-align:center;margin-bottom:30px'>
    <h1 style='color:#8bd100;margin:0;font-size:34px'>AFTERHOUR</h1>
    <p style='color:#555;font-size:11px;margin:4px 0 0;letter-spacing:3px'>BILLIARD &amp; LOUNGE PREMIUM</p>
  </div>
  <h2 style='text-align:center;font-size:26px'>🎱 Selamat Bergabung!</h2>
  <p style='color:#ccc;text-align:center;margin-bottom:24px'>Halo <strong style='color:#8bd100'>{$name}</strong>, akun Afterhour Member Anda kini aktif.</p>
  <div style='background:#1a0a0e;border:1px solid #2f0618;border-radius:12px;padding:24px;margin-bottom:24px'>
    <h3 style='color:#8bd100;margin:0 0 16px'>Keuntungan Member Anda:</h3>
    <ul style='color:#ccc;padding-left:20px;margin:0;line-height:2'>
      <li>Booking meja Regular, VIP Smoking, &amp; VVIP</li>
      <li>Diskon eksklusif di 6 outlet Afterhour</li>
      <li>Notifikasi promo langsung ke email</li>
      <li>Riwayat booking &amp; konfirmasi pembayaran real-time</li>
    </ul>
  </div>
  <div style='text-align:center;margin-bottom:24px'>
    <a href='#' style='display:inline-block;background:#8bd100;color:#000;padding:14px 40px;border-radius:30px;text-decoration:none;font-weight:bold;font-size:15px'>MULAI BOOKING</a>
  </div>
  <p style='text-align:center;color:#333;font-size:11px;margin:0'>© Afterhour Billiard &amp; Lounge &bull; All rights reserved</p>
</div>
</body>
</html>";
    }
}
?>
