<?php
$message = '';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "php/config.php"; // config.php dosyanızın doğru yolu üzerinden include edin.

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($password == $confirm_password) {
                $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                if (mysqli_num_rows($sql) > 0) {
                    $message = "$email - This email already exists!";
                } else {
                    $verification_code = md5(uniqid(rand(), true));

                    if (isset($_FILES['image'])) {
                        $img_name = $_FILES['image']['name'];
                        $img_type = $_FILES['image']['type'];
                        $tmp_name = $_FILES['image']['tmp_name'];

                        $img_explode = explode('.', $img_name);
                        $img_ext = end($img_explode);

                        $allowed_extensions = ["jpeg", "png", "jpg"];
                        if (in_array($img_ext, $allowed_extensions) && in_array($img_type, ["image/jpeg", "image/jpg", "image/png"])) {
                            $time = time();
                            $new_img_name = $time . $img_name;
                            if (move_uploaded_file($tmp_name, "php/images/" . $new_img_name)) {
                                $ran_id = rand(time(), 100000000);
                                $status = "Inactive";
                                $encrypt_pass = md5($password); // Güvenlik nedeniyle md5 kullanımı, daha güçlü şifreleme yöntemleri tercih edilebilir.
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status, verification_code)
                                    VALUES ({$ran_id}, '{$fname}', '{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}', '{$verification_code}')");

                                if ($insert_query) {
                                    // E-posta gönderme işlemi
                                    $mail = new PHPMailer(true);
                                    try {
                                        // SMTP ayarları
                                        $mail->isSMTP();
                                        $mail->Host       = 'smtp.gmail.com';
                                        $mail->SMTPAuth   = true;
                                        $mail->Username   = 'rastgel05@gmail.com';
                                        $mail->Password   = 'wdnc gjuv cgqk nolw';
                                        $mail->SMTPSecure = 'tls';
                                        $mail->Port       = 587;
                                        $mail->SMTPOptions = array(
                                            'ssl' => array(
                                                'verify_peer' => false,
                                                'verify_peer_name' => false,
                                                'allow_self_signed' => true
                                            )
                                        );

                                        // Alıcı bilgileri
                                        $mail->setFrom('rastgel05@gmail.com', 'On Dokuz Mayıs Üniversitesi'); // Gönderen adı ve e-posta adresi
                                        $mail->addAddress($email, $fname); // Alıcı adı ve e-posta adresi

                                        // E-posta içeriği
                                        $mail->isHTML(true);
                                        $mail->Subject = 'Doğrulama Kodu';
                                        $mail->Body    = "Merhaba {$fname},<br><br>Üyeliğinizi tamamlamak için aşağıdaki doğrulama kodunu kullanın:<br><br><strong>{$verification_code}</strong>";

                                        // Gönder
                                        $mail->send();
                                        
                                        $message = "<div>Kaydınız başarıyla oluşturuldu. Lütfen e-postanızı kontrol edin ve hesabınızı doğrulayın.</div>";
                                    } catch (Exception $e) {
                                        $message = "<div>E-posta gönderme hatası: {$mail->ErrorInfo}</div>";
                                    }
                                } else {
                                    $message = "<div>Something went wrong. Please try again!</div>";
                                }
                            } else {
                                $message = "<div>There was an error uploading your image.</div>";
                            }
                        } else {
                            $message = "<div>Please upload an image file - jpeg, png, jpg</div>";
                        }
                    }
                }
            } else {
                $message = "<div>Passwords do not match!</div>";
            }
        } else {
            $message = "<div>$email is not a valid email!</div>";
        }
    } else {
        $message = "<div>All input fields are required!</div>";
    }
}
?>
