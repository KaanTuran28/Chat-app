<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: users.php");
  }
?>

<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="form signup">
      <header>Chat App</header>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
        <div class="name-details">
          <div class="field input">
            <label>Ad</label>
            <input type="text" name="fname" placeholder="Ad" required>
          </div>
          <div class="field input">
            <label>Soyad</label>
            <input type="text" name="lname" placeholder="Soyad" required>
          </div>
        </div>
        <div class="field input">
          <label>Email Adres</label>
          <input type="text" name="email" placeholder="Email Adres" required>
        </div>
        <div class="field input">
          <label>Şifre</label>
          <input type="password" name="password" placeholder="Şifre" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field input">
          <label>Şifre Tekrar Gir</label>
          <input type="password" name="confirm_password" placeholder="Şifre Tekrar Gir" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field image">
          <label>Resim Seç</label>
          <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Sohbete Başla">
        </div>
      </form>
      <div class="link">Hesabın Varmı? <a href="login.php">Giriş Yap</a></div>
    </section>
  </div>

  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/signup.js"></script>
</body>
</html>