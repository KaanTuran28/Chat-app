<?php
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Veritabanı bağlantısı ve form verilerini alalım
    include_once "php/config.php"; 

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
                    // E-posta gönderme kısmını kaldırdık, sadece veritabanı işlemi yapıyoruz
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
                                $encrypt_pass = md5($password);
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status, verification_code)
                                    VALUES ({$ran_id}, '{$fname}', '{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}', '{$verification_code}')");

                                if ($insert_query) {
                                    $message = "<div>Kaydınız başarıyla oluşturuldu. Hesabınızı doğrulamak için e-posta göndermek gerekli değildir.</div>";
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
