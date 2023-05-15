<?php 
  function twopoints_on_earth($latitudeFrom, $longitudeFrom,
  $latitudeTo,  $longitudeTo)
{
$long1 = deg2rad($longitudeFrom);
$long2 = deg2rad($longitudeTo);
$lat1 = deg2rad($latitudeFrom);
$lat2 = deg2rad($latitudeTo);

// Haversine Formula
$dlong = $long2 - $long1;
$dlati = $lat2 - $lat1;

$val = pow(sin($dlati/2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong/2), 2);

$res = 2 * asin(sqrt($val));

// radius value in kilometers
$radius = 6371;

return ($res * $radius);
}

?>
<?php include('templates/header.php'); 
if(!isset($_SESSION))
  session_start();
$id=$_SESSION['userid'];

$sql = "SELECT * FROM user_details where id = $id";

$result = mysqli_query($conn,$sql);

$donor_details = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <style>
        .donate-container {
          background: /*  linear-gradient(rgba(0,0,0,0.3),rgba(0,0,0,0.6)),*/url("images/donate.jpg"); 
          background-size: cover;
          background-repeat: no-repeat;
          height: 100vh;
        }
        .flexbox {
          display: flex;
          justify-content: center;
          align-items: center;
          gap: 20px;
        }

        .donate-container h1 {
          color: white;
          text-align: center;
          text-shadow: 0 5px 10px rgba(0,0,0);
        }

        .donate-box h2 {
            text-align: center;
            color: white;
            text-shadow: 0 5px 10px rgba(0,0,0);
            margin-bottom: 10px;
        }
        
        .donate-box {
          position: absolute;
          top: 60%;
          left: 50%;
          transform: translate(-50%,-50%);
          border-radius: 20px;
          padding: 30px;
          background-color: rgba(0,0,0,0.5);
          box-shadow: 7px 7px 20px #000;
        }

        .donate-box .input-group input {
          width: 250px;
          padding: 5px;
          margin: 5px;
          margin-left: 10px;
          border-radius: 2px;
          border: none;
          outline: none;
        }

        .btn {
          margin-left: 250px;
          padding: 5px;
          margin-top: 10px;
        }

        .error {
          margin-left: 10px;
          color: red;
        }

        label {
          color: white;
        }
        .file {
          width: 250px;
          margin: 2px;
          margin-left: 7px;
          border-radius: 2px;
          border: none;
          outline: none;
        }

        ::placeholder {
          color: black;
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
<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

    include('config/connect.php');
    $sql = "SELECT username,email,latitude,longitude FROM user_details WHERE user_type = 'receiver' ";
    $result = mysqli_query($conn,$sql);
    $receiverDetails = mysqli_fetch_all($result,MYSQLI_ASSOC);

    $insert='';
    $insertTest=0;
    $name = $lpic = $phone = $address = $district = $state = $pincode = $food = $quantity = $time = '';
    $errors = array('name'=>'','lpic'=>'','phone' => '','address' => '','district' => '','state'=>'','pincode'=>'','food' => '','quantity' => '','time' => '');

    if(isset($_POST['submit']))
    {

      if(empty($_POST['dname'])) {
        $errors['name'] = 'Name is required!!!';
      }
      else {
        $name = $_POST['dname'];
        if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
          $errors['name'] = 'Name must be letters and spaces only';
        }
      }
      if(empty($_FILES['lpic']['name']))
      {
        $errors['lpic'] = 'Image is required!';
      }
      else if(getimagesize($_FILES['lpic']['tmp_name'])==false) {
        $errors['lpic'] = "Image is required";
      }
      else {
        $lpic = $_FILES['lpic']['tmp_name'];
        $name = $_FILES['lpic']['name'];
  
        $lpic = file_get_contents($lpic);
        $lpic = base64_encode($lpic); 
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
    if(empty($_POST['address'])) {
      $errors['address'] = 'Address is required!!!';
    }
    else {
      $address = $_POST['address'];
    }
    if(empty($_POST['district'])) {
      $errors['district'] = 'District is required!!!';
    }
    else {
      $district = $_POST['district'];
      if(!preg_match('/^[a-zA-Z\s]+$/', $district)){
        $errors['district'] = 'District must be letters and spaces only';
      }
    }
    if(empty($_POST['state'])) {
      $errors['state'] = 'State is required!!!';
    }
    else {
      $state = $_POST['state'];
      if(!preg_match('/^[a-zA-Z\s]+$/', $state)){
        $errors['state'] = 'Name must be letters and spaces only';
      }
    }

    if(empty($_POST['pincode'])) {
      $errors['pincode'] = 'Pincode is required!!!';
    }
    else {
      $pincode = $_POST['pincode'];
      if (!preg_match('/^[0-9]+$/', $pincode)) {
        $errors['pincode'] = 'Pincode must contain only numbers!';
      }
    }

    if(empty($_POST['food'])) {
      $errors['food'] = 'Food is required!!!';
    }
    else {
      $food = $_POST['food'];
    }

    if(empty($_POST['quantity'])) {
      $errors['quantity'] = 'Quantity is required!!!';
    }
    else {
      $quantity = $_POST['quantity'];
    }
      if(empty($_POST['time'])) {
        $errors['time'] = 'Time is required!!!';
      }
      else {
        $time = $_POST['time'];
      }
    if (array_filter($errors))
    {

    }
    else {
      $name = mysqli_real_escape_string($conn, $_POST['dname']);
      $phone = mysqli_real_escape_string($conn, $_POST['phone']);
      $address = mysqli_real_escape_string($conn, $_POST['address']);
      $state = mysqli_real_escape_string($conn, $_POST['state']);
      $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
      $district = mysqli_real_escape_string($conn, $_POST['district']);
      $food = mysqli_real_escape_string($conn, $_POST['food']);//explode(',',$_POST['food']);
      $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);//explode(',',$_POST['quantity']);
      $time = mysqli_real_escape_string($conn, $_POST['time']);
      $id = $_SESSION['userid'];
      $insert = "INSERT INTO donor(donor_name,picture,phone,address,district,state,pincode,food,quantity,time,id) VALUES('$name','$lpic','$phone','$address','$district','$state','$pincode','$food','$quantity','$time','$id')";
      
      // foreach($food as $key=>$fooditem) {
      //   $quantityitem = $quantity[$key];
      //   $insert = "INSERT INTO donor(donor_name,picture,phone,address,district,state,pincode,food,quantity,time) VALUES('$name','$lpic','$phone','$address','$district','$state','$pincode','$fooditem','$quantityitem','$time')";
        
      $insertTest = mysqli_query($conn,$insert);

  }
}

if($insertTest) {
  if(isset($_SESSION) &&  $_SESSION['userType']=='donor') {
    $sql = "SELECT latitude,longitude FROM user_details WHERE id = '$id'  ";
    $result = mysqli_query($conn,$sql);
    $donorDetails = mysqli_fetch_all($result,MYSQLI_ASSOC);
    
    $donorLatitude = $donorDetails[0]['latitude'];
    $donorLongitude = $donorDetails[0]['longitude'];

    foreach($receiverDetails as $receiverDetail) {
      $receiverLatitude = $receiverDetail['latitude'];
      $receiverLongitude = $receiverDetail['longitude'];
      $distance = twopoints_on_earth($donorLatitude, $donorLongitude, $receiverLatitude, $receiverLongitude).' '.'kilometers';
      if($distance <= 12) {
        if(!class_exists('PHPMailer\PHPMailer\Exception'))
        {
          require 'PHPMailer/src/Exception.php';
          require 'PHPMailer/src/PHPMailer.php';
          require 'PHPMailer/src/SMTP.php';
        }
          

          $mail = new PHPMailer(true);
          try {  
            $mail->isSMTP();                                        
           $mail->Host       = 'smtp.gmail.com';                     
           $mail->SMTPAuth   = true;                                  
          $mail->Username   = 'donorftn@gmail.com';                     
          $mail->Password   = 'lqygfnkacrerpymd';                             
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
            $mail->Port       = 465;       

            $mail->setFrom('from@example.com', 'Feed The Need');
            $mail->addAddress($receiverDetail['email']);     //Add a recipient

           $message = "Dear ".$receiverDetail['username'].",<br>
                I hope this email finds you well. I am writing to inform you that  ".$name." has expressed their interest in donating ".$food." to your organization.
                We want to help you. So, you can order the food via Feed The Need website.<br><br><br>
                Regards,<br>
                Sakthilakshmi M,<br>
                Social Activist.<br>
                ";        $mail->isHTML(true);                                
    $mail->Subject = $name.' wants to donate!';
    $mail->Body    =  $message;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
} catch (Exception $e) {
   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
      }
    }
  }
}

if($insertTest) {
  $sql = "SELECT donor_id FROM donor WHERE id = '$id'  ";
  $result = mysqli_query($conn,$sql);
  $donorID = mysqli_fetch_all($result,MYSQLI_ASSOC);
  $d_id = $donorID[0]['donor_id'];
  $food = explode(',',$_POST['food']);
  $quantity = explode(',',$_POST['quantity']);

  foreach($food as $key=>$fooditem) {
    $quantityitem = $quantity[$key];
    $insert = "INSERT INTO food(donor_id,food_item,quantity) VALUES('$d_id','$fooditem','$quantityitem')";
    if(mysqli_query($conn,$insert))
    {
      $insertTest = true;
    }
  }

 }

    
?>

  <div class="donate-container">
    <h1>Alone we can do so little!
              Together we can do so much!</h1>
    <div class="donate-box">
    <h2>DONATE</h2>
    <form action="donate.php" method="POST" enctype="multipart/form-data" >
    <div class="flexbox">
    <div class="box1">
      <div class="input-group">
        <input type = "text" name="dname" id="dname" placeholder="Name" value="<?php echo $donor_details['username']; ?>" readonly>
        <div class="error"><?php echo $errors['name'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="phone" id="phone" placeholder="Mobile Number" autocomplete="off">
        <div class="error"><?php echo $errors['phone'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="address" id="address" placeholder="Address" autocomplete="off">
        <div class="error"><?php echo $errors['address'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="district" id="district" placeholder="District" autocomplete="off">
        <div class="error"><?php echo $errors['district'] ?></div>
      </div>
      <div class="input-group file">
        <label for="lpic">Location picture:</label>
        <input type = "file" name="lpic" id="lpic">
        <div class="error"><?php echo $errors['lpic'] ?></div>
      </div>
      </div>
      <div class="box">
      <div class="input-group">
        <input type = "text" name="state" id="state" placeholder="State" autocomplete="off">
        <div class="error"><?php echo $errors['state'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="pincode" id="pincode" placeholder="Pincode" autocomplete="off">
        <div class="error"><?php echo $errors['pincode'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="food" id="food" placeholder="Food Items [Eg: Idli,Poori]" autocomplete="off">
        <div class="error"><?php echo $errors['food'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="quantity" id="quantity" placeholder="Quantity in kgs[Eg: 2,3]" autocomplete="off">
        <div class="error"><?php echo $errors['quantity'] ?></div>
      </div>
      <div class="input-group">
        <label for="time">Preparation Time: </label>
        <input type = "datetime-local" name="time" id="time">
        <div class="error"><?php echo $errors['time'] ?></div>
      </div>
      </div>
    </div>
      <input type="submit" name="submit" id="don" value="Submit" class="btn">
    </form>
  </div>
</div>

<dialog class="dialog-box-1">
    <img src="images/success.gif" alt="" width="300px" height = "300px">
    <p>Thank you for donating!</p>
    <a href="index.php"><button class="close" onclick=closebtn() >Ok</button></a>
</dialog>

<?php
    if($insertTest)
    {
      echo "<script>
                document.querySelector('.dialog-box-1').style.display = 'block';
            </script>";
    }
?>

  <?php include('templates/footer.php'); ?>