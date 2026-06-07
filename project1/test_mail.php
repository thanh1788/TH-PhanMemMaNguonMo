<?php
/**
 * Test gửi email SMTP
 * Truy cập: http://localhost/project1/test_mail.php?to=email_nhan@gmail.com
 * XÓA FILE NÀY SAU KHI TEST XONG!
 */
require_once 'app/helpers/MailHelper.php';

$to = $_GET['to'] ?? '';

echo '<meta charset="UTF-8">';
echo '<style>body{font-family:Arial;padding:30px;background:#f5f5f5;} .box{background:#fff;padding:24px;border-radius:12px;max-width:600px;box-shadow:0 2px 12px rgba(0,0,0,.08);} .ok{color:green;font-weight:bold;} .err{color:red;font-weight:bold;} pre{background:#1a1a1a;color:#0f0;padding:16px;border-radius:8px;font-size:13px;overflow:auto;}</style>';
echo '<div class="box">';
echo '<h2>🔧 Test SMTP Mail</h2>';

if (empty($to)) {
    echo '<p>Thêm <code>?to=email_cua_ban@gmail.com</code> vào URL để test.</p>';
    echo '<p>Ví dụ: <a href="?to=tanthanh15825@gmail.com">?to=tanthanh15825@gmail.com</a></p>';
} else {
    echo "<p>Đang gửi email test tới: <strong>" . htmlspecialchars($to) . "</strong>...</p>";

    $subject = 'Test email từ MixiTech - ' . date('H:i:s d/m/Y');
    $body    = "<h2>✅ Email gửi thành công!</h2>
                <p>Đây là email test từ hệ thống <strong>MixiTech Store</strong>.</p>
                <p>Thời gian gửi: <strong>" . date('H:i:s d/m/Y') . "</strong></p>
                <p>Nếu bạn nhận được email này, cấu hình SMTP Gmail đã hoạt động đúng.</p>";

    $result = MailHelper::send($to, $subject, $body);

    if ($result) {
        echo '<p class="ok">✅ Gửi thành công! Kiểm tra hộp thư của bạn.</p>';
        echo '<p class="ok">(Kể cả thư mục Spam/Junk nếu không thấy trong Inbox)</p>';
    } else {
        echo '<p class="err">❌ Gửi thất bại! Xem chi tiết lỗi trong <code>app/logs/emails/</code></p>';
    }

    echo '<hr>';
    echo '<p><strong>Log file:</strong> <code>app/logs/emails/</code></p>';
}

echo '</div>';
echo '<br><p style="color:red;font-weight:bold;">⚠️ XÓA FILE NÀY SAU KHI TEST XONG!</p>';
