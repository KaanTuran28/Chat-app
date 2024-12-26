<?php
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Veritabanı bağlantısını ve form verilerini alalım
    include_once __DIR__ . '/config.php';  // Dosya aynı klasörde ise

    // Form verilerini alalım
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Formda gerekli tüm alanların dolu olup olmadığını kontrol edelim
    if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        // Geçerli bir e-posta adresi olup olmadığını kontrol edelim
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Şifrelerin eşleşip eşleşmediğini kontrol edelim
            if ($password == $confirm_password) {
                // Aynı e-posta adresinin daha önce kaydedilip edilmediğini kontrol edelim
                $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                if (mysqli_num_rows($sql) > 0) {
                    $message = "<div>$email - Bu e-posta zaten kayıtlı!</div>";
                } else {
                    // Benzersiz doğrulama kodu oluşturuyoruz
                    $verification_code = md5(uniqid(rand(), true));

                    // Resim yükleme işlemi
                    if (isset($_FILES['image'])) {
                        $img_name = $_FILES['image']['name'];
                        $img_type = $_FILES['image']['type'];
                        $tmp_name = $_FILES['image']['tmp_name'];

                        // Resim uzantısını kontrol etme
                        $img_explode = explode('.', $img_name);
                        $img_ext = strtolower(end($img_explode));

                        $allowed_extensions = ["jpeg", "png", "jpg"];
                        if (in_array($img_ext, $allowed_extensions) && in_array($img_type, ["image/jpeg", "image/jpg", "image/png"])) {
                            // Resmi doğru klasöre yükleyelim
                            $time = time();
                            $new_img_name = $time . $img_name;

                            // 'images/' dizini altında yükleme
                            $upload_dir = __DIR__ . "/images/"; 
                            if (!is_dir($upload_dir)) {
                                mkdir($upload_dir, 0777, true);  // Eğer dizin yoksa oluştur
                            }

                            if (move_uploaded_file($tmp_name, $upload_dir . $new_img_name)) {
                                // Kullanıcı bilgilerini veritabanına ekleyelim
                                $ran_id = rand(time(), 100000000);
                                $status = "Inactive"; // Kullanıcı durumu "Inactive"
                                $encrypt_pass = md5($password); // Şifreyi MD5 ile şifreliyoruz
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status, verification_code)
                                    VALUES ({$ran_id}, '{$fname}', '{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}', '{$verification_code}')");

                                if ($insert_query) {
                                    $message = "<div>Kaydınız başarıyla oluşturuldu. Hesabınızı doğrulamak için e-posta göndermek gerekli değildir.</div>";
                                } else {
                                    $message = "<div>Bir şeyler ters gitti. Lütfen tekrar deneyin!</div>";
                                }
                            } else {
                                $message = "<div>Resminizi yüklerken bir hata oluştu.</div>";
                            }
                        } else {
                            $message = "<div>Lütfen geçerli bir resim dosyası yükleyin - jpeg, png, jpg.</div>";
                        }
                    }
                }
            } else {
                $message = "<div>Şifreler eşleşmiyor!</div>";
            }
        } else {
            $message = "<div>$email geçerli bir e-posta adresi değil!</div>";
        }
    } else {
        $message = "<div>Tüm alanlar doldurulmalıdır!</div>";
    }
}
?>
