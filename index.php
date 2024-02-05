<?php
session_start();
include_once "php/config.php";
include_once "php/signup.php";

// Eğer form gönderilmişse ve kayıt işlemi başarılıysa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($insert_query) && $insert_query) {
    echo "<script>window.location.href='e_posta_kontrol.php';</script>";
    exit(); // Yönlendirmenin ardından kodun devam etmemesi için exit kullanılır
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 8px;
    }

    input {
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        padding: 10px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }
</style>

<body>
    <div class="container">
        <?php echo $message; ?>

        <form method="post" action="" enctype="multipart/form-data">
            <label for="fname">İsim:</label>
            <input type="text" name="fname" required>

            <label for="lname">Soyisim:</label>
            <input type="text" name="lname" required>

            <label for="email">E-posta:</label>
            <input type="email" name="email" required>

            <label for="password">Şifre:</label>
            <input type="password" name="password" required>

            <label for="confirm_password">Şifre Tekrar:</label>
            <input type="password" name="confirm_password" required>

            <label for="image">Profil Resmi Seçin:</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit">Kayıt Ol</button>
        </form>
    </div>
</body>
</html>
