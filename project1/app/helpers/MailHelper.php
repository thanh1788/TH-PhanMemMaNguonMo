<?php
/**
 * MailHelper - Gửi email qua Gmail SMTP (TLS port 587)
 * Không cần thư viện ngoài, dùng PHP socket thuần.
 *
 * Cấu hình tại phần SMTP CONFIG bên dưới.
 */
class MailHelper
{
    // ============================================================
    // ⚙️  SMTP CONFIG — Chỉnh sửa tại đây
    // ============================================================
    private static string $smtpHost     = 'smtp.gmail.com';
    private static int    $smtpPort     = 587;
    private static string $smtpUser     = 'tanthanh15825@gmail.com'; // Gmail của bạn
    private static string $smtpPass     = 'itwf qgug pofm iwlj';     // App Password (16 ký tự)
    private static string $fromEmail    = 'tanthanh15825@gmail.com';
    private static string $fromName     = 'MixiTech Store';

    // Lưu log email vào file (bật để debug, tắt khi production)
    private static bool   $saveLog      = true;
    private static string $logDir       = 'app/logs/emails/';
    // ============================================================

    /**
     * Gửi email HTML qua SMTP
     */
    public static function send(string $to, string $subject, string $htmlBody): bool
    {
        // Luôn lưu log để có thể kiểm tra
        if (self::$saveLog) {
            self::writeLog($to, $subject, $htmlBody);
        }

        try {
            return self::sendSmtp($to, $subject, $htmlBody);
        } catch (\Exception $e) {
            // Lưu lỗi vào log
            self::writeLog($to, '[LỖI SMTP] ' . $subject, '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>');
            return false;
        }
    }

    /**
     * Gửi email xác thực tài khoản
     */
    public static function sendVerification(string $to, string $name, string $token): bool
    {
        $link    = self::getBaseUrl() . '/Auth/verify/' . $token;
        $subject = '[MixiTech] Xác thực tài khoản của bạn';
        $body    = self::template($subject,
            "<p>Xin chào <strong>" . htmlspecialchars($name) . "</strong>,</p>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>MixiTech</strong>. Vui lòng nhấn nút bên dưới để xác thực email:</p>
            <p style='text-align:center; margin: 30px 0;'>
                <a href='{$link}' style='background:#d70018;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:bold;font-size:15px;display:inline-block;'>
                    ✅ Xác thực tài khoản
                </a>
            </p>
            <p style='color:#999;font-size:13px;'>Hoặc copy link này vào trình duyệt:<br>
                <a href='{$link}' style='color:#d70018;word-break:break-all;'>{$link}</a>
            </p>
            <p style='color:#999;font-size:13px;'>⏰ Link có hiệu lực trong <strong>24 giờ</strong>.<br>
            Nếu bạn không đăng ký, hãy bỏ qua email này.</p>"
        );
        return self::send($to, $subject, $body);
    }

    /**
     * Gửi email đặt lại mật khẩu
     */
    public static function sendResetPassword(string $to, string $name, string $token): bool
    {
        $link    = self::getBaseUrl() . '/Auth/resetPassword/' . $token;
        $subject = '[MixiTech] Đặt lại mật khẩu';
        $body    = self::template($subject,
            "<p>Xin chào <strong>" . htmlspecialchars($name) . "</strong>,</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Nhấn nút bên dưới để tiếp tục:</p>
            <p style='text-align:center; margin: 30px 0;'>
                <a href='{$link}' style='background:#d70018;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:bold;font-size:15px;display:inline-block;'>
                    🔐 Đặt lại mật khẩu
                </a>
            </p>
            <p style='color:#999;font-size:13px;'>Hoặc copy link này vào trình duyệt:<br>
                <a href='{$link}' style='color:#d70018;word-break:break-all;'>{$link}</a>
            </p>
            <p style='color:#999;font-size:13px;'>⏰ Link có hiệu lực trong <strong>1 giờ</strong>.<br>
            Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>"
        );
        return self::send($to, $subject, $body);
    }

    // ============================================================
    // SMTP ENGINE — PHP socket thuần, hỗ trợ STARTTLS
    // ============================================================

    private static function sendSmtp(string $to, string $subject, string $htmlBody): bool
    {
        $host     = self::$smtpHost;
        $port     = self::$smtpPort;
        $user     = self::$smtpUser;
        $pass     = self::$smtpPass;
        $from     = self::$fromEmail;
        $fromName = self::$fromName;

        // Xóa khoảng trắng trong App Password nếu có (Google hay hiển thị "xxxx xxxx xxxx xxxx")
        $pass = str_replace(' ', '', $pass);

        // Kết nối TCP tới smtp.gmail.com:587
        $socket = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$socket) {
            throw new \Exception("Không kết nối được SMTP: $errstr ($errno)");
        }
        stream_set_timeout($socket, 10);

        // Helper đọc phản hồi từ server
        $read = function () use ($socket): string {
            $resp = '';
            while ($line = fgets($socket, 512)) {
                $resp .= $line;
                // Dòng cuối không có dấu '-' sau mã lệnh
                if (substr($line, 3, 1) === ' ') break;
            }
            return $resp;
        };

        // Helper gửi lệnh và kiểm tra mã phản hồi
        $cmd = function (string $command, int $expected) use ($socket, $read): string {
            fwrite($socket, $command . "\r\n");
            $resp = $read();
            $code = (int)substr($resp, 0, 3);
            if ($code !== $expected) {
                throw new \Exception("SMTP lỗi sau '$command': $resp");
            }
            return $resp;
        };

        // 1. Đọc banner chào
        $banner = $read();
        if ((int)substr($banner, 0, 3) !== 220) {
            throw new \Exception("SMTP banner lỗi: $banner");
        }

        // 2. EHLO
        $cmd("EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost'), 250);

        // 3. STARTTLS — nâng cấp kết nối lên TLS
        $cmd("STARTTLS", 220);

        // Bật crypto với SSL context dùng cacert.pem của Laragon
        $caFile = 'C:/laragon/etc/ssl/cacert.pem';
        $cryptoMethod = STREAM_CRYPTO_METHOD_TLS_CLIENT;
        stream_context_set_option($socket, 'ssl', 'verify_peer',       true);
        stream_context_set_option($socket, 'ssl', 'verify_peer_name',  true);
        stream_context_set_option($socket, 'ssl', 'peer_name',         'smtp.gmail.com');
        if (file_exists($caFile)) {
            stream_context_set_option($socket, 'ssl', 'cafile', $caFile);
        }

        if (!stream_socket_enable_crypto($socket, true, $cryptoMethod)) {
            throw new \Exception("Không thể bật TLS");
        }

        // 4. EHLO lần 2 sau TLS
        $cmd("EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost'), 250);

        // 5. AUTH LOGIN
        $cmd("AUTH LOGIN", 334);
        $cmd(base64_encode($user), 334);
        $cmd(base64_encode($pass), 235);

        // 6. Khai báo người gửi & nhận
        $cmd("MAIL FROM:<{$from}>", 250);
        $cmd("RCPT TO:<{$to}>",     250);

        // 7. Gửi nội dung email (DATA)
        $cmd("DATA", 354);

        // Build email headers + body
        $boundary = md5(uniqid((string)time()));
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $encodedFrom    = '=?UTF-8?B?' . base64_encode($fromName) . '?= <' . $from . '>';

        $message  = "From: {$encodedFrom}\r\n";
        $message .= "To: <{$to}>\r\n";
        $message .= "Subject: {$encodedSubject}\r\n";
        $message .= "MIME-Version: 1.0\r\n";
        $message .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
        $message .= "X-Mailer: MixiTech-PHP-Mailer/1.0\r\n";
        $message .= "\r\n";
        // Plain text fallback
        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= chunk_split(base64_encode(strip_tags($htmlBody))) . "\r\n";
        // HTML part
        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= chunk_split(base64_encode($htmlBody)) . "\r\n";
        $message .= "--{$boundary}--\r\n";
        $message .= ".";

        $cmd($message, 250);

        // 8. Kết thúc phiên
        fwrite($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private static function template(string $title, string $content): string
    {
        return "<!DOCTYPE html>
<html lang='vi'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
</head>
<body style='margin:0;padding:0;background:#f5f5f5;font-family:Arial,Helvetica,sans-serif;'>
  <table width='100%' cellpadding='0' cellspacing='0' border='0'>
    <tr><td align='center' style='padding:30px 10px;'>
      <table width='600' cellpadding='0' cellspacing='0' border='0'
             style='background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);max-width:600px;'>
        <!-- HEADER -->
        <tr>
          <td style='background:#d70018;padding:24px 32px;'>
            <h1 style='margin:0;color:#ffffff;font-size:24px;font-weight:800;letter-spacing:-0.5px;'>
              📱 MixiTech
            </h1>
            <p style='margin:4px 0 0;color:rgba(255,255,255,0.8);font-size:13px;'>
              Hệ thống bán lẻ công nghệ
            </p>
          </td>
        </tr>
        <!-- BODY -->
        <tr>
          <td style='padding:32px;color:#333333;font-size:15px;line-height:1.7;'>
            <h2 style='margin-top:0;color:#1a1a1a;font-size:20px;'>{$title}</h2>
            {$content}
          </td>
        </tr>
        <!-- FOOTER -->
        <tr>
          <td style='background:#f8f8f8;padding:16px 32px;text-align:center;
                     color:#999999;font-size:12px;border-top:1px solid #eeeeee;'>
            © 2026 MixiTech Vietnam &nbsp;·&nbsp; 475A Điện Biên Phủ, TP.HCM<br>
            <span style='color:#cccccc;'>Email này được gửi tự động, vui lòng không trả lời.</span>
          </td>
        </tr>
      </table>
    </td></tr>
  </table>
</body>
</html>";
    }

    private static function getBaseUrl(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host;
    }

    private static function writeLog(string $to, string $subject, string $body): void
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }
        $safe     = preg_replace('/[^a-z0-9._-]/i', '_', $to);
        $filename = self::$logDir . date('Y-m-d_H-i-s') . '_' . $safe . '.html';
        $log      = "<!-- TO: {$to} | SUBJECT: {$subject} | TIME: " . date('Y-m-d H:i:s') . " -->\n" . $body;
        file_put_contents($filename, $log);
    }
}
