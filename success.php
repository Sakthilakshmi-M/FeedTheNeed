<html>
<?php include('templates/header.php'); ?>
<head>
  <style>
    .container {
      border: 5px solid black;
      border-radius: 10px;
      padding: 10px;
      margin-top: 50px;
      background-color: aliceblue;
    }

    .container input {
      width: 300px;
      margin-bottom: 10px;
      border: 2px solid black;
      padding: 10px;
      border-radius: 10px;
    }

    .container h3{
      padding: 20px;
    }

    .container p {
      padding: 10px;
    }

    .btn {
      margin-top: 10px;
        display: inline-block;
        background: #333;
        color: #fff;
        font-size: 17px;
        border-radius: 5px;
        padding: 8px 25px;
        transition: all .5s linear;
    }

  </style>
</head>
<?php
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\SMTP;
 use PHPMailer\PHPMailer\Exception;
  include('config/connect.php');
  // include('templates/header.php');
  if(!isset($_SESSION)) {
    session_start(); 
  }
  $msg1 = '';
  $mail_status = false;
  $r_id = $_SESSION['userid'];
  
  $sql = "SELECT * FROM orders where r_id=$r_id";

  $result = mysqli_query($conn,$sql);

  $orders = mysqli_fetch_all($result,MYSQLI_ASSOC);

  $donor_id = $orders[0]['donor_id'];

  $sql = "SELECT * FROM user_details WHERE id = $donor_id";

  $result = mysqli_query($conn,$sql);

  $donor_details = mysqli_fetch_assoc($result);

  
  $sql = "SELECT * FROM user_details WHERE id = $r_id";

  $result = mysqli_query($conn,$sql);

  $receiver_details = mysqli_fetch_assoc($result);



  if(!isset($_SESSION['otp'])) {
    $otp = rand(100000,999999);
    $_SESSION['otp'] = $otp;
  }
  else {
    $otp = $_SESSION['otp'];
  }
?>
<?php 
   $name = $phone = '';
   $errors = array('name'=>'','phone' => '');
   
   if(isset($_POST['submit'])) {
    if(empty($_POST['dname'])) {
      $errors['name'] = 'Name is required!!!';
    }
    else {
      $name = $_POST['dname'];
      if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
        $errors['name'] = 'Name must be letters and spaces only';
      }
    }

    if(empty($_POST['phone'])) {
      $errors['phone'] = 'Mobile number is required!!!';
    }
    else {
      $phone = $_POST['phone'];
      if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = 'Mobile number must contain 10 digits only';
    }
   }

   if(array_filter($errors)) {
    print_r($errors);
    echo "Hello";
   }
   else {
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
  
    $mail = new PHPMailer(true);
    try {  
      $mail->isSMTP();                                        
      $mail->Host       = 'smtp.gmail.com';                     
      $mail->SMTPAuth   = true;                                  
      $mail->Username   = 'receiverftn@gmail.com';                     
      $mail->Password   = '';                           
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
      $mail->Port       = 465;       
  
      $mail->setFrom('from@example.com', 'Feed The Need');
      $mail->addAddress('donorftn@gmail.com');  
      foreach($orders as $order) {
        $msg1 = $msg1 . "<li>" . $order['food'] . " - " . $order['quantity']. " kg</li>";

      }   
      $message = "Dear ".$donor_details['username'].",<br><br>
      Your order is accepted by " . $receiver_details['username'].".<br>
      Here are the details of the person who will be coming to collect the food.<br><br>
      <p>Person Name: ".$name."</p>
      <p>Person Mobile Number: ".$phone."</p>
      <p>OTP: " . $_SESSION['otp']."</p>
      <p>Food Items</p>
      <ul>".$msg1;
 
      "</ul>";
      $mail->isHTML(true);                                
      $mail->Subject ='Details of person coming to collect the food!';
      $mail->Body    =  $message;
      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      $mail->send();

      $mail_status = true;
      if($mail_status) {
        $sql = "DELETE from orders where r_id='$r_id'";
        mysqli_query($conn,$sql);
        echo "<script>window.location.href = 'index.php';</script>";
      }
    }
   catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    
   }
   
  }
?>
<?php

?>
<center>
  <div class="container">
  <h1>Your Order is Confirmed! You can come and pickup the food.</h1>
<h3>Enter the details of the person coming to collect the food</h3>
<p>Share this otp with <?php echo $donor_details['username'] ?>: <?php echo $_SESSION['otp']; ?> </p>
<form action="success.php" method="POST">
  <div class="input-group">
      <input type="text" name="dname" id="dname" value="<?php echo htmlspecialchars($name)?>" autocomplete="off" placeholder="User Name">
      <div class="error"><?php echo $errors['name']; ?></div>
  </div>
  <div class="input-group">
    <input type = "text" name="phone" id="phone" placeholder="Mobile Number">
    <div class="error"><?php echo $errors['phone'] ?></div>
  </div>
  <input type="submit" value="Submit" name="submit" class="btn">
</form>
  </div>
</center>

<?php

?>
