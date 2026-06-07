<?php
/**
 * Chạy file này 1 lần để lấy hash password đúng cho migration
 * Truy cập: http://localhost/your-project/generate_hash.php
 * SAU KHI DÙNG XONG, XÓA FILE NÀY ĐI!
 */
$passwords = [
    'admin123' => password_hash('admin123', PASSWORD_BCRYPT),
    'user123'  => password_hash('user123',  PASSWORD_BCRYPT),
];

echo '<pre style="background:#1a1a1a;color:#0f0;padding:20px;font-family:monospace;">';
echo "=== GENERATED BCRYPT HASHES ===\n\n";
foreach ($passwords as $plain => $hash) {
    echo "Password: {$plain}\n";
    echo "Hash:     {$hash}\n";
    echo "Verify:   " . (password_verify($plain, $hash) ? "OK ✅" : "FAIL ❌") . "\n\n";
}

echo "\n=== SQL UPDATE STATEMENTS ===\n\n";
echo "UPDATE users SET password = '" . $passwords['admin123'] . "' WHERE email = 'admin@mixitech.vn';\n";
echo "UPDATE users SET password = '" . $passwords['user123']  . "' WHERE email = 'user@mixitech.vn';\n";
echo '</pre>';
echo '<p style="color:red;font-weight:bold;">⚠️ XÓA FILE NÀY NGAY SAU KHI DÙNG XONG!</p>';
