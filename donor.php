<html>
  <?php  include('templates/header.php'); ?>
  <head>
    <title>FeedTheNeed</title>
    <style>
      .dcontainer {
        padding: 15px 9%;
      }
      .box-container {
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(270px,1fr));
        margin: 0 auto;
        gap: 15px;
        justify-content: center;
        align-items: center;
      }
      .dcontainer .heading {
        text-align: center;
        margin: 10px;
        text-shadow: 0 5px 10px rgba(0,0,0,0.2);
      }

      .dcontainer .box-container .box {
        box-shadow: 0 5px 10px rgba(0,0,0,.2);
        border-radius: 5px;
        background: #fff;
        text-align: center;
        padding: 30px 20px;
        transition: all .2s linear;
        
      }
      .dcontainer .box-container .box:hover {
        transform: scale(1.02);
      }

      .dcontainer .box-container .box h3 {
        color: #444;
        font-size: 22px;
        padding: 10px 0;
      }

      .dcontainer .box-container .box p {
        color: #777;
        font-size: 15px;
        line-height: 1.8;
      }

      .dcontainer .box-container .box .btn {
        margin-top: 10px;
        display: inline-block;
        background: #333;
        color: #fff;
        font-size: 17px;
        border-radius: 5px;
        padding: 8px 25px;
        transition: all .5s linear;
      }

      .dcontainer .box-container .box .btn:hover{
        background: #555;
      }
    </style>
<body>
<div class="dcontainer">

<h1 class="heading">Our Donors</h1>

 <div class="box-container">
     
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

<?php
  include('config/connect.php');

  if(!isset($_SESSION))
  {
    session_start();
    $id = $_SESSION['userid'];
  }
  else {
    $id = $_SESSION['userid'];
  }

  $sql = "SELECT latitude,longitude from user_details where id = $id";
  $result = mysqli_query($conn,$sql);
  $receiverDetails = mysqli_fetch_all($result,MYSQLI_ASSOC);
  $receiverLatitude = $receiverDetails[0]['latitude'];
  $receiverLongitude = $receiverDetails[0]['longitude'];

  $sql = "SELECT id,latitude,longitude from user_details where user_type = 'donor' ";

  $result = mysqli_query($conn,$sql);

  $donors = mysqli_fetch_all($result,MYSQLI_ASSOC);
  
  foreach($donors as $donor) {
    $donorLatitude = $donor['latitude'];
    $donorLongitude = $donor['longitude'];
    $distance = twopoints_on_earth($donorLatitude, $donorLongitude, $receiverLatitude, $receiverLongitude).' '.'kilometers';

    if($distance<=11) {
      $id = $donor['id'];
      $sql = "SELECT * FROM donor where id = $id";
      $result = mysqli_query($conn,$sql);
      $reqDonors = mysqli_fetch_all($result,MYSQLI_ASSOC); ?>
            <?php foreach($reqDonors as $reqDonor) { ?>
        <div class="box">
        <?php echo "<img height='80px'src='data:image;base64,{$reqDonor["picture"]}'alt='img'>" ?>
        <h3><?php echo htmlspecialchars($reqDonor['donor_name'])?></h3>
        <p><?php echo htmlspecialchars($reqDonor['district'])?></p>
        <a href="donorDetails.php?id=<?php echo htmlspecialchars($reqDonor['donor_id']); ?>" class="btn">Know More</a>
        </div>
     <?php }

    }


  } ?>
    </div>
</div>
<!-- ?>
<?php
  // include('config/connect.php');
  // include('templates/header.php');
  // $sql = 'SELECT * FROM donor';

  // $result = mysqli_query($conn,$sql);

  // $donors = mysqli_fetch_all($result,MYSQLI_ASSOC);
  // if(isset($_SESSION['userid'])) {
  //   $usersql = "SELECT * FROM user_details WHERE id = {$_SESSION['userid']}";

  //   $result = mysqli_query($conn,$usersql);
  //   $users = mysqli_fetch_all($result,MYSQLI_ASSOC);
  // }
  // mysqli_free_result($result);
  // mysqli_close($conn);
?>

  <head>
    <title>FeedTheNeed</title>
    <style>
      .dcontainer {
        padding: 15px 9%;
      }
      .box-container {
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(270px,1fr));
        margin: 0 auto;
        gap: 15px;
        justify-content: center;
        align-items: center;
      }
      .dcontainer .heading {
        text-align: center;
        margin: 10px;
        text-shadow: 0 5px 10px rgba(0,0,0,0.2);
      }

      .dcontainer .box-container .box {
        box-shadow: 0 5px 10px rgba(0,0,0,.2);
        border-radius: 5px;
        background: #fff;
        text-align: center;
        padding: 30px 20px;
        transition: all .2s linear;
        
      }
      .dcontainer .box-container .box:hover {
        transform: scale(1.02);
      }

      .dcontainer .box-container .box h3 {
        color: #444;
        font-size: 22px;
        padding: 10px 0;
      }

      .dcontainer .box-container .box p {
        color: #777;
        font-size: 15px;
        line-height: 1.8;
      }

      .dcontainer .box-container .box .btn {
        margin-top: 10px;
        display: inline-block;
        background: #333;
        color: #fff;
        font-size: 17px;
        border-radius: 5px;
        padding: 8px 25px;
        transition: all .5s linear;
      }

      .dcontainer .box-container .box .btn:hover{
        background: #555;
      }
    </style>
  </head>
  <body>
  <div class="dcontainer">
  <h1 class="heading">Our Donors</h1>

<div class="box-container">
      <?php foreach($donors as $donor) { ?>
    <div class="box">
        <?php echo "<img height='80px'src='data:image;base64,{$donor["picture"]}'alt='img'>" ?>
        <h3><?php echo htmlspecialchars($donor['donor_name'])?></h3>
        <p><?php echo htmlspecialchars($donor['district'])?></p>
        <a href="donorDetails.php?id=<?php echo htmlspecialchars($donor['donor_id']); ?>" class="btn">Know More</a>
    </div>
    <?php } ?>
   
  </div>
</div>
<?php include('templates/footer.php'); ?>  -->
