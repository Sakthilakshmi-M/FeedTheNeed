<!DOCTYPE html>
<html>

<?php
  include('config/connect.php');
  include('templates/header.php');
  $sql = 'SELECT * FROM deliverer';

  $result = mysqli_query($conn,$sql);

  $deliverers = mysqli_fetch_all($result,MYSQLI_ASSOC);

  if(isset($_SESSION['userid'])) {
    $usersql = "SELECT * FROM user_details WHERE id = {$_SESSION['userid']}";

    $result = mysqli_query($conn,$usersql);
    $users = mysqli_fetch_all($result,MYSQLI_ASSOC);
  }
  mysqli_free_result($result);
  mysqli_close($conn);
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
  <h1 class="heading">Our Receivers</h1>

<div class="box-container">
      <?php foreach($deliverers as $deliverer) { ?>
    <div class="box">
        <?php echo "<img height='100px' width='100px'src='data:image;base64,{$deliverer["rpic"]}'alt='img'>" ?>
        <h3><?php echo htmlspecialchars($deliverer['name'])?></h3>
        <p><?php echo htmlspecialchars($deliverer['district'])?></p>
        <a href="#" class="btn">Know More</a>
    </div>
    <?php } ?>
   
  </div>
</div>
<?php include('templates/footer.php'); ?> 