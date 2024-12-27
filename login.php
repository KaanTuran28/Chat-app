<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: users.php");
  }
?>

<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="form login">
      <header>Chat App</header>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
        <div class="field input">
          <label>Email Ades</label>
          <input type="text" name="email" placeholder="Email Ades" required>
        </div>
        <div class="field input">
          <label>Şifre</label>
          <input type="password" name="password" placeholder="Şifre" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Sohbete Başla">
        </div>
      </form>
      <div class="link">Hesabın Yokmu? <a href="index.php">Kaydol </a></div>
    </section>
  </div>
  
  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/login.js"></script>

</body>
</html>
