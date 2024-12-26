<?php
session_start();
include("php/config.php");

// Oturum kontrolü
if (!isset($_SESSION['unique_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['unique_id'];

// Kullanıcı bilgilerini çekme
$sql = $conn->prepare("SELECT * FROM users WHERE unique_id = ?");
$sql->bind_param("s", $user_id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fname = htmlspecialchars($row['fname']);
    $lname = htmlspecialchars($row['lname']);
    $email = htmlspecialchars($row['email']);
    $img = htmlspecialchars($row['img']);
    $status = htmlspecialchars($row['status']);
} else {
    echo json_encode(["status" => "error", "message" => "Kullanıcı bulunamadı!"]);
    exit;
}

// AJAX ile gelen istekleri işleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $new_fname = htmlspecialchars(trim($_POST['fname']));
    $new_lname = htmlspecialchars(trim($_POST['lname']));
    $new_email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if ($new_email === false) {
        echo json_encode(["status" => "error", "message" => "Geçersiz e-posta adresi!"]);
        exit();
    }

    // Profil fotoğrafı yükleme işlemi
    $new_img = $img; // Varsayılan resim
    if (isset($_FILES['img']) && $_FILES['img']['error'] === 0) {
        $img_name = $_FILES['img']['name'];
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_extensions = ["jpeg", "jpg", "png"];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($img_ext, $allowed_extensions) || $_FILES['img']['size'] > $max_size) {
            echo json_encode(["status" => "error", "message" => "Geçersiz dosya formatı veya boyutu!"]);
            exit();
        }

        $new_img = "profile_" . uniqid() . "." . $img_ext; // Benzersiz isim
        $upload_dir = "php/images/";
        if (!move_uploaded_file($img_tmp_name, $upload_dir . $new_img)) {
            echo json_encode(["status" => "error", "message" => "Fotoğraf yüklenemedi."]);
            exit();
        }
    }

    // Veritabanında güncelleme
    $update_sql = $conn->prepare("UPDATE users SET fname = ?, lname = ?, email = ?, img = ? WHERE unique_id = ?");
    $update_sql->bind_param("sssss", $new_fname, $new_lname, $new_email, $new_img, $user_id);
    
    if ($update_sql->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Profil başarıyla güncellendi!",
            "data" => [
                "fname" => $new_fname,
                "lname" => $new_lname,
                "email" => $new_email,
                "img" => $new_img
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Güncelleme hatası: " . $conn->error]);
    }
    exit();
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

<form id="profileForm" enctype="multipart/form-data">
    <label for="fname">Ad:</label>
    <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" required>

    <label for="lname">Soyad:</label>
    <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" required>

    <label for="email">E-posta:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

    <label for="img">Profil Fotoğrafı:</label>
    <input type="file" id="img" name="img" accept="image/*">

    <button type="submit">Güncelle</button>
</form>

<div id="message"></div>

<a href="chat.php">Geri Dön</a>

<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault(); 
    const formData = new FormData(this);
    formData.append('action', 'update_profile');

    fetch('profil.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = data.message;
        if (data.status === 'success') {
            // Profil detaylarını güncelle
            document.getElementById('fname').value = data.data.fname;
            document.getElementById('lname').value = data.data.lname;
            document.getElementById('email').value = data.data.email;
        }
    })
    .catch(error => console.error('Hata:', error));
});
</script>

</body>
</html>

