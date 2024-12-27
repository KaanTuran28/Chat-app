<?php
session_start();
include("php/config.php");

// Oturum kontrolü
if (!isset($_SESSION['unique_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['unique_id'];

// Kullanıcı bilgilerini çek
$sql = "SELECT * FROM users WHERE unique_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fname = htmlspecialchars($row['fname']);
    $lname = htmlspecialchars($row['lname']);
    $email = htmlspecialchars($row['email']);
    $img = htmlspecialchars($row['img']);
} else {
    echo "Kullanıcı bulunamadı!";
    exit;
}

// Profil güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $new_fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $new_lname = mysqli_real_escape_string($conn, $_POST['lname']); 
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    $img_name = $img; // Varsayılan olarak eski resim

    // Yeni profil fotoğrafı yüklendiyse işleme al
    if (!empty($_FILES['img']['name'])) {
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ["jpeg", "jpg", "png"];

        if (in_array($img_ext, $allowed_ext)) {
            $new_img_name = time() . '.' . $img_ext;
            $upload_dir = __DIR__ . "/images/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            if (move_uploaded_file($img_tmp_name, $upload_dir . $new_img_name)) {
                $img_name = $new_img_name;
            } else {
                echo "Resim yükleme sırasında bir hata oluştu.";
                exit;
            }
        } else {
            echo "Lütfen geçerli bir resim formatı yükleyin (jpeg, jpg, png).";
            exit;
        }
    }

    // Veritabanını güncelle
    $update_sql = "UPDATE users SET fname = ?, lname = ?, email = ?, img = ? WHERE unique_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssss", $new_fname, $new_lname, $new_email, $img_name, $user_id);

    if ($stmt->execute()) {
        echo "Profil başarıyla güncellendi!";
        // Yeni bilgileri yansıtmak için sayfayı yenileyin
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Güncelleme hatası: " . $conn->error;
    }
}

// Profil silme işlemi
if (isset($_POST['delete'])) {
    $delete_sql = "DELETE FROM users WHERE unique_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $user_id);

    if ($stmt->execute()) {
        session_destroy(); // Kullanıcının oturumunu sonlandır
        header("Location: login.php");
        exit();
    } else {
        echo "Silme hatası: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profil.css">
    <title>Kullanıcı Profil Ayarları</title>
</head>
<body>
<h1>Kullanıcı Profil Ayarları</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <label for="fname">Ad:</label>
    <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" required>

    <label for="lname">Soyad:</label>
    <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" required>

    <label for="email">E-posta:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

    <label for="img">Profil Fotoğrafı:</label>
    <input type="file" id="img" name="img" accept="image/*">
    <img src="images/<?php echo $img; ?>" alt="Profil Fotoğrafı" style="max-width: 100px; display: block;">

    <button type="submit" name="update">Güncelle</button>
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" name="delete" onclick="return confirm('Profilinizi silmek istediğinize emin misiniz?')">Profil Sil</button>
</form>

<a href="users.php">Çıkış</a>
</body>
</html>
