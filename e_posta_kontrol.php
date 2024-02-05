<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-posta Doğrulama</title>
</head>
<body>
    <h2>E-posta Doğrulama</h2>
    
    <?php
    // Eğer form gönderilmişse
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include_once "php/config.php"; // config.php dosyasını doğru yolu üzerinden include edin.

        $entered_code = mysqli_real_escape_string($conn, $_POST['verification_code']);

        // Kullanıcının girdiği kodu MD5 ile şifrele ve veritabanındakiyle karşılaştır
        $entered_code_md5 = md5($entered_code);
        $check_code_query = mysqli_query($conn, "SELECT * FROM users WHERE verification_code = '{$entered_code_md5}' AND status = 'Inactive'");
        
        if (mysqli_num_rows($check_code_query) > 0) {
            // Eğer doğrulama kodu doğruysa, kullanıcının hesabını aktifleştir
            $activate_account = mysqli_query($conn, "UPDATE users SET status = 'Active' WHERE verification_code = '{$entered_code_md5}'");

            if ($activate_account) {
                echo "<p style='color: green;'>Hesabınız başarıyla doğrulandı. Giriş yapabilirsiniz.</p>";
            } else {
                echo "<p style='color: red;'>Hesabınız doğrulanırken bir hata oluştu.</p>";
            }
        } else {
            echo "<p style='color: red;'>Geçersiz doğrulama kodu. Lütfen tekrar deneyin.</p>";
        }
    }
    ?>

    <form method="post" action="login.php">
        <label for="verification_code">Doğrulama Kodu:</label>
        <input type="text" id="verification_code" name="verification_code" required>
        <button type="submit">Doğrula</button>
    </form>
</body>
</html>
