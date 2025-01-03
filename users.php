<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php 
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
          <img src="php/images/<?php echo htmlspecialchars($row['img']); ?>" alt="Profil Fotoğrafı">
          <div class="details">
            <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>
        <button onclick="window.location.href='profil.php'" class="profil" style="  width: 80px;
  height: 80px;
  border: none;
  background: none;
  outline: none;
  cursor:pointer"><i class="fa-solid fa-gears" style="font-size: 25px;"></i></button>
        <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Çıkış</a>
      </header>
      <div class="search">
        <span class="text">Sohbete başla..</span>
        <input type="text" placeholder="Aranacak adı girin...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
  
      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>
</body>
</html>

