<?php 
  include('config/connect.php');
  if(!isset($_SESSION)) {
  session_start();
  }
  if(isset($_SESSION['userid']))
  {
    
    $sql = "SELECT * FROM user_details WHERE id = {$_SESSION['userid']}";

    $result = mysqli_query($conn,$sql);

    $user = mysqli_fetch_assoc($result);
  }
?>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feed The Need</title>
</head>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins';
}

li {
  list-style-type: none;
}

a {
  text-decoration: none;
}

header {
  display: flex;
  width: 100%;
  justify-content: space-between;
  background-color: rgba(255,255,255);
  border-bottom: solid white;
  box-shadow: 0 3px 5px rgba(0, 0, 0, 0.4);
}

header img {
  width: 200px;
  height: 50px;
}

header ul {
  display: flex;
  width: 50%;
}
header ul li {
  display: flex;
  justify-content: space-between;
  margin: auto;
}
header ul li a {
  color: #2E404B;
  font-family: 'Poppins', sans-serif;
  font-weight: bold;
  transition: 0.4s linear;
}

header ul li a:hover {
  background-color: #c4d1ec;
  border-radius: 10%;
  padding: 0 12px;
}

section {
  background: url("images/ftn1.jpg");
  height: 110vh;
  background-size: cover;
  background-position: center;;
}

.hamburger {
  display: none;
}
@media screen and (max-width: 800px) {
  header ul {
    display: none;
  }

  .hamburger {
    display: block;
    margin-top: 15px;
    margin-right: 10px;;
  }

  .hamburger .line {
    display: block;
    width: 30px;
    height: 5px;
    margin-bottom: 3px;
    background-color: black;
    border-radius: 2px;
  }
}

.dialog-box {
  max-width: 50ch;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  border: none;
  outline: none;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 5px 10px rgba(255,255,255,0.5);
}

.dialog-box::backdrop {
  background-color: rgba(0,0,0,0.7);
}

dialog p {
  text-align: center;
  font-size: 20px;
  color: red;
  font-weight: bolder;
}

dialog button {
  margin-top: 20px;
  margin-left: 100px;
  width: 100px;
  padding: 5px;
  outline: none;
  border: none;
  background-color: red;
  color: white;
  font-size: 15px;
  border-radius: 10px;
}

</style>
<body>
  <header>
  <img class="logo" src="images/logo.png" class="bgcolor">
    <ul>
      <li><a href="index.php">Home</a></li>
      <?php if(isset($_SESSION['userid']) && $_SESSION['userType']=='donor')  { ?>
      <li><a href="donate.php">Donate</a></li>
      <li><a href="logout.php">Logout</a></li>
      <?php } else if(isset($_SESSION['userid']) && $_SESSION['userType']=='receiver'){ ?>
        <li><a href="donor.php">Donors</a></li>
        <li><a href="receiver.php">Receivers</a></li>
        <li><a href="logout.php">Logout</a></li>
      <?php } else if(isset($_SESSION['userid']) && $_SESSION['userType']=='deliverer') { ?>
        <li><a href="location.php">Add locations</a></li>
        <li><a href="logout.php">Logout</a></li>
      <?php } else { ?>
      <li><a class="open-1">Donate</a></li>
      <li><a class="open-2">Donors</a></li>
      <li><a class="open-3">Receivers</a></li>
      <li><a href="#" onclick=login()>Login</a></li>
      <li><a href="#" onclick=register()>Register</a></li>
      <?php } ?>


      <!-- <li><a href="donor.php">Donor</a></li>
      <li><a href="#">Receiver</a></li>
      <li><a href="#">Deliver</a></li>
      <li><a href="#" onclick=login()>Login</a></li>
      <li><a href="#" onclick=register()>Register</a></li> -->
    
    </ul>
    <div class="hamburger">
      <span class="line"></span>
      <span class="line"></span>
      <span class="line"></span>
    </div>
  </header>
  <dialog class="dialog-box">
    <img src="images/error.gif" alt="" width="300px" height = "300px">
    <p>Please login to continue! </p>
    <button class="close" onclick=closebtn() >Ok</button>
  </dialog>
  </div>
  <script>
    const dialogbox = document.querySelector('.dialog-box');
    const open1 = document.querySelector('.open-1');
    const open2 = document.querySelector('.open-2');
    const open3 = document.querySelector('.open-3');
    const open4 = document.querySelector('.open-4');
    const close = document.querySelector('.close');
    
      open1.addEventListener("click", ()=> {
      dialogbox.showModal();
      })

      open2.addEventListener("click", ()=> {
      dialogbox.showModal();
      })

      open3.addEventListener("click", ()=> {
      dialogbox.showModal();
      })

      open4.addEventListener("click",()=>{
        dialogbox.showModal();
      })

      function closebtn() {
      close.addEventListener("click", ()=> {
      dialogbox.close();
    })
      }
  
  </script>