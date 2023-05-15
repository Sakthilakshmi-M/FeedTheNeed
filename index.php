<?php
  include('config/connect.php');
  $name=$email=$password=$userType=$cpassword=$latitude=$longitude='';
  $errors = array('name'=>'','email' => '','password' => '','userType' => '','cpassword'=>'','latitude'=>'','longitude'=>'');
  $insertTrue=false;
if(isset($_POST['submit']))
{
  if(empty($_POST['username'])) {
    $errors['name'] = 'Username is required !';
  }
  else {
    $name = $_POST['username'];
    if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
      $errors['name'] = 'Username must be letters and spaces only !';
    }
  }

  if(empty($_POST['latitude'])) {
    $errors['latitude'] = 'Latitude is required !';
  }
  else {
    $latitude = $_POST['latitude'];
    if(!preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $latitude)){
      $errors['latitude'] = 'Invalid latitude !';
    }
  }

  if(empty($_POST['longitude'])) {
    $errors['longitude'] = 'Longitude is required !';
  }
  else {
    $longitude = $_POST['longitude'];
    if(!preg_match('/^[-+]?([1-9]|[1-9]\d|1[0-7]\d)(\.\d+)?|180(\.0+)?$/', $longitude)){
      $errors['longitude'] = 'Invalid longitude !';
    }
  }

  if(empty($_POST['email'])) {
    $errors['email'] = 'Email is required !';
  }
  else {
    $email = $_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors['email'] = 'Email must be a valid email address !';
    }
  }

  if(empty($_POST['password'])) {
    $errors['password'] = 'Password is required !';
  }
  else if(strlen($_POST['password'])<8)
  {
    $errors['password']='Password must contain atleast 8 characters !';
  }
  else {
    $password = $_POST['password'];
  }

  if(empty($_POST['cpassword'])) {
    $errors['cpassword'] = 'Confirm Password is required!!!';
  }
  else if($_POST['cpassword']!==$_POST['password'])
  {
    $errors['cpassword']='Password mismatch';
  }
  else {
    $cpassword = $_POST['password'];
  }

  if(empty($_POST['user'])) {
    $errors['userType'] = 'Choose the type of user!!';
  }
  else if($_POST['user']=='usertype')
    $errors['userType'] = 'Choose the type of user!!';
  else {
    $userType = $_POST['user'];
  }

  $sql = "SELECT * FROM user_details WHERE email = '$email' ";
  $result = mysqli_query($conn,$sql);
  $rowcount = mysqli_num_rows($result);

  if($rowcount>0)
  {
    $errors['email'] = 'Email already exists!!';
  }

  if (array_filter($errors))
  {
  
  }
  else {
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));
    $userType = mysqli_real_escape_string($conn, $_POST['user']);
    $insert = "INSERT INTO user_details(username,email,password,user_type,latitude,longitude) VALUES('$name','$email','$password','$userType','$latitude','$longitude')";
    $insertTrue = mysqli_query($conn,$insert);
    if($insertTrue) {
   
        if($userType == 'receiver')
        {
        header('Location: receiverDetails.php');
        }

        else if($userType == 'deliverer') {
         header('Location: delivererDetails.php');
        }

    }
     else {
    echo 'query error: '. mysqli_error($conn);
   }

  }
}
?>
<?php
  include('config/connect.php');
  $luser=0;
  $lpassword='';
  $lemail='';
  $lerrors=array('lemail'=>'','lpassword'=>'');
  if(isset($_POST['login']))
  {
    if(empty($_POST['lemail'])) {
      $lerrors['lemail']='Enter email!';
    }
    else {
      $lemail = $_POST['lemail'];
      $lsql = "SELECT * FROM user_details where email = '$lemail'";
      $lresult = mysqli_query($conn,$lsql);
      $luser = mysqli_fetch_array($lresult,MYSQLI_ASSOC);
    }

    if(empty($_POST['lpassword'])) {
      $lerrors['lpassword']='Password is required!!';
    }
    else {
      $lpassword = $_POST['lpassword'];
    }
    if($luser) {
      if(md5($lpassword)==$luser['password'])
      {
        session_start();
        $_SESSION['userid']=$luser['id'];
        $_SESSION['userType']=$luser['user_type'];
        
        if($_SESSION['userType']=='donor') {
          header('Location: donate.php');
        }
        
      }
      else {
        $lerrors['lpassword']="Password doesn't match";
      }
    }
    else {
      $lerrors['lemail'] = "Email id doesn't exist!";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
  .container {
  position: absolute;
  top: 57%;
  left: 80%;
  transform: translate(-50%,-50%);
  background: linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.9));
  width: 380px;
  padding: 15px 30px;
  border-radius: 10px;
  box-shadow: 10px 10px 10px #000;
}

.container input, .container select {
  width: 100%;
  padding: 3px;
  margin: 10px;
  border-radius: 5px;
  color: black;
  font-weight: bold;
  border: none;
  outline: none;
}

.container input:focus {
  outline:none
}

.container h1 {
  text-align: center;
  color: #fff;
}

.error {
  color: red;
}

.login1 {
  color: white;
}

.align {
  display: flex;
  justify-content: space-between;
}
.container h1 {
  text-align: center;
}

.register {
  display: none;
}

.login {
  display: none;
}

.login1 {
  padding: 10px;
}
.dialog-box-1 {
          display: none;
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

        .dialog-box-1 p {
          text-align: center;
          font-size: 20px;
          color: green;
        }

        .dialog-box-1::backdrop {
            background-color: rgba(0,0,0);
          }


        .dialog-box-1 button {
          margin-top: 20px;
          margin-left: 100px;
          width: 100px;
          padding: 5px;
          outline: none;
          border: none;
          background-color: green;
          color: white;
          font-size: 15px;
          border-radius: 10px;
        }

  
 </style>
</head>
<?php include('templates/header.php'); ?>
<section>
<div class="container register">
    <div class="align">
      <h1></h1>
      <i class="fa-solid fa-xmark" style="color: red;" onclick=registerOff()></i>
    </div>
    <h1>REGISTER</h1>
    <form action="index.php" method="POST">
    <div class="input-group">
      <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($name)?>" autocomplete="off" placeholder="User Name">
      <div class="error"><?php echo $errors['name']; ?></div>
    </div>
    <div class="input-group">
      <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email)?>" autocomplete="off" placeholder="Email">
      <div class="error"><?php echo $errors['email']; ?></div>
    </div>
    <div class="input-group">
      <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($password)?>" autocomplete="off" placeholder="Password">
      <div class="error"><?php echo $errors['password']; ?></div>
    </div>
    <div class="input-group">
      <input type="password" name="cpassword" id="cpassword" value="<?php echo htmlspecialchars($password)?>" autocomplete="off" placeholder="Confirm Password">
      <div class="error"><?php echo $errors['cpassword']; ?></div>
    </div>
    <div class="input-group">
      <input type="text" name="latitude" id="latitude" value="<?php echo htmlspecialchars($latitude)?>" autocomplete="off" placeholder="Latitude">
      <div class="error"><?php echo $errors['latitude']; ?></div>
    </div>
    <div class="input-group">
      <input type="text" name="longitude" id="longitude" value="<?php echo htmlspecialchars($longitude)?>" autocomplete="off" placeholder="Longitude">
      <div class="error"><?php echo $errors['longitude']; ?></div>
    </div>
    <div class="input-group">
      <select id="user" name="user">
        <option value="usertype">-- Select User Type --</option>
        <option value="donor">Donor</option>
        <option value="receiver">Receiver</option>
      </select>
      <div class="error"><?php echo $errors['userType']; ?></div>
    </div>
    <input type="submit" name="submit" value="submit" id="sub1">
  </form>
  <?php
  if(array_filter($errors)) {
    echo "<script>document.querySelector('.register').style.display = 'block';</script>";
  } 
  ?>
</div>

<div class="container login">
  <div class="align">
      <h1></h1>
      <i class="fa-solid fa-xmark" style="color: red;" onclick=loginOff()></i>
    </div>
    <h1>LOGIN</h1>
    <form action="index.php" method="post">
    <div class="input-group">
      <input type="text" name="lemail" id="lemail"  autocomplete="off" placeholder="Email" value=<?php echo htmlspecialchars($lemail); ?>>
      <div class="error"><?php echo $lerrors['lemail']; ?></div>
    </div>
    <div class="input-group">
      <input type="password" name="lpassword" id="lpassword"  autocomplete="off" placeholder="Password">
      <div class="error"><?php echo $lerrors['lpassword']; ?></div>
    </div>
    <input type="submit" name="login" value="Login">
    <p class="login1" onclick=register()>New user?</p>
  </form>
  <?php if(array_filter($lerrors)) {
    echo "<script>document.querySelector('.login').style.display = 'block';</script>";
  }
  ?>
  </div>
</section>


<dialog class="dialog-box-1">
    <img src="images/success.gif" alt="" width="300px" height = "300px">
    <p>Successfully registered!<br>Please login to continue!</p>
    <a href="index.php"><button class="close" onclick=closebtn() >Ok</button></a>
</dialog>
<?php if($insertTrue) {
  echo "True";
    echo "<script>
    document.querySelector('.dialog-box-1').style.display = 'block';
   </script>";
  } ?>
<script>
  function register() {
    document.querySelector('.register').style.display = 'block';
    loginOff();
  }

  function registerOff() {
    document.querySelector('.register').style.display = 'none';   
  }

  function login() {
    document.querySelector('.login').style.display = "block";
    registerOff();
  }

  function loginOff() {
    document.querySelector('.login').style.display = "none";

  }
  
</script>

<?php include('templates/footer.php'); ?>

