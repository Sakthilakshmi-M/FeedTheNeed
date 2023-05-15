<?php 
     include('config/connect.php');
?>
<?php
  $name = $dpic =  $phone = $address = $district = $state = $pincode = $location='';
  $errors = array('name'=>'','phone' => '','address' => '','district' => '','state'=>'','pincode'=>'','location' =>'','dpic'=>'');

  if(isset($_POST['deliverer']))
  {

    if(empty($_POST['name'])) {
      $errors['name'] = 'Name is required!!!';
    }
    else {
      $name = $_POST['name'];
      if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
        $errors['name'] = 'Name must be letters and spaces only';
      }
    }

    if(empty($_FILES['dpic']['name']))
    {
      $errors['dpic'] = 'Image is required!';
    }
    else if(getimagesize($_FILES['dpic']['tmp_name'])==false) {
      $errors['dpic'] = "Image is required";
    }
    else {
      $dpic = $_FILES['dpic']['tmp_name'];
      $name = $_FILES['dpic']['name'];

      $dpic = file_get_contents($dpic);
      $dpic = base64_encode($dpic); 
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
  
  if(empty($_POST['location'])) {
    $errors['location'] = 'Location is required!!!';
  }
  else {
    $location = $_POST['location'];
  }

  if (array_filter($errors))
  {
      print_r($errors);                      
  }
  else {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $insert = "INSERT INTO deliverer(name,rpic,phone,address,district,state,pincode,location) VALUES('$name','$dpic','$phone','$address','$district','$state','$pincode','$location')";
    
    if(mysqli_query($conn,$insert)) {
      header('Location: index.php');
    }
    else
    {

    }
  
  }
}
?>
<html>
  <?php   include('templates/header.php'); ?>
  <head>
    <style>
           .donate-container {
          background: linear-gradient(rgba(0,0,0,0.3),rgba(0,0,0,0.6)),url("images/donate.jpg");
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
          padding: 3px 5px;
          margin-left: 90px;
          border: none;
          outline: none;
          border-radius: 3px;
          margin-top: 10px;
          color: white;
          background-color: blue;
          font-weight: bolder;
          font-size: 15px;
          transition: all .5s linear;
        }

        .btn:hover {
          background-color: white;
          color: black;
        }

        .error {
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
    </style>
  </head>
  <form action="delivererDetails.php" method="POST" enctype="multipart/form-data">
  <div class="donate-container">
  <h1>Alone we can do so little!
              Together we can do so much!</h1>
  <div class="donate-box">
  <h2>UPDATE PROFILE</h2>
  <div class="box">
      <div class="input-group">
        <input type = "text" name="name" id="name" placeholder="Name">
        <div class="error"><?php echo $errors['name'] ?></div>
      </div>
      <div class="input-group file">
        <label for="dpic">Passport Picture: </label>
        <input type = "file" name="dpic" id="dpic">
        <div class="error"><?php echo $errors['dpic'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="phone" id="phone" placeholder="Mobile Number">
        <div class="error"><?php echo $errors['phone'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="address" id="address" placeholder="Address">
        <div class="error"><?php echo $errors['address'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="district" id="district" placeholder="District">
        <div class="error"><?php echo $errors['district'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="state" id="state" placeholder="State">
        <div class="error"><?php echo $errors['state'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="pincode" id="pincode" placeholder="Pincode">
        <div class="error"><?php echo $errors['pincode'] ?></div>
      </div>
      <div class="input-group">
        <input type = "text" name="location" id="location" placeholder="Add locations you can deliver">
        <div class="error"><?php echo $errors['location'] ?></div>
      </div>
      <input type="submit" name="deliverer" value="Update" class="btn">
  </div>
  </div>
  </div>
  </form>