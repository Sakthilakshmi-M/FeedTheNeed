<html>

<head>
    <style>
      table,tr,th, td{
        border: 1px solid black;
        border-collapse: collapse;
        padding: 20px;
      }
      tr,th {
        width: 30%;
      }

      tr:nth-child(even) {
        background-color: #D6EEEE;
      }

      h1 {
        margin-bottom: 20px;
      }

      .btn {
          margin-top: 20px;
        display: inline-block;
        background: #333;
        color: #fff;
        font-size: 17px;
        border-radius: 5px;
        padding: 8px 25px;
        transition: all .5s linear;
        border: none;
        outline: none;
        }

        .btn a{
          color: white;
        }

        td {
          text-align: center;
        }
    </style>
</head>
<?php
  include('config/connect.php');
  include('templates/header.php');
  if(!isset($_SESSION)) {
    session_start();
    
  }
?>

<?php 
  $count = $zeroCount = 0;
  if(isset($_GET['id']))
  {
    $r_id = $_SESSION['userid'];
    $id = mysqli_real_escape_string($conn,$_GET['id']);

    $sql = "SELECT * FROM donor WHERE donor_id = $id ";

    $result = mysqli_query($conn,$sql);

    $donorDetails = mysqli_fetch_assoc($result);

    $sql = "SELECT * FROM food WHERE donor_id = $id";

    $result = mysqli_query($conn,$sql);

    $foodDetails = mysqli_fetch_all($result,MYSQLI_ASSOC);
  }

  if (isset($_POST['order'])) {
   foreach($foodDetails as $foodDetail) {
    $count+=1;
    $food = str_replace(' ', '', $foodDetail['food_item']);
    $quantity = $_POST[$food];
    $foods = $foodDetail['food_item'];
    $donor_id = $donorDetails['id'];
    $insert = "INSERT INTO orders(donor_id,food,quantity,r_id) VALUES('$donor_id','$foods','$quantity',$r_id)";
    mysqli_query($conn,$insert);
    $sql = "UPDATE food set quantity = quantity-$quantity where food_item= '$foods'";
    if(mysqli_query($conn,$sql)) {
      echo "Updated succesfully";
    } 
    $sql = "SELECT quantity from food where food_item = '$foods'";
    $result = mysqli_query($conn,$sql);
    $zeroCheck = mysqli_fetch_assoc($result);
    if($zeroCheck['quantity']==0)
    {
      $zeroCount+=1;
      $sql = "DELETE FROM food WHERE food_item ='$foods'";
      if(mysqli_query($conn,$sql)){
        echo "Deleted successfullly";
      }
    }
   }
   if($count == $zeroCount) {
    $id = $donorDetails['donor_id'];
    $sql = "DELETE FROM donor WHERE donor_id ='$id'";
    if(mysqli_query($conn,$sql)) {
      echo "<script>alert('Order Success');
      window.location.href = 'success.php';
      </script>";
    
    }
   }
   else {
    echo "<script>alert('Order Success');
          window.location.href = 'success.php';</script>";

   }
  }
?>

<body>
  <?php if(isset($donorDetails['donor_name'])) { ?>
    <center>
    <h1>Order from <?php echo htmlspecialchars($donorDetails['donor_name']); ?></h1>
    <form action="order.php?id=<?php echo $id; ?>" method="POST">
      <table>
        <tr>
          <th>Food Item</th>
          <th>Available Quantity</th>
          <th>Required Quantity</th>
        </tr>
        <?php foreach($foodDetails as $foodDetail) {  ?>
          <tr>
            <?php $food = str_replace(' ', '', $foodDetail['food_item']); ?>
            <td><?php echo htmlspecialchars($foodDetail['food_item']); ?></td>
            <td><?php echo htmlspecialchars($foodDetail['quantity']); ?></td>
            <td><input type="number" id="<?php echo $food; ?>" name="<?php echo $food; ?>" min="1" max="<?php echo $foodDetail['quantity']; ?>"></td>
        </tr>
        <?php } ?>
      </table>
      <input type="submit" id="order" value="order" name="order" class="btn">
    </form>
  </center>
  <?php } ?>
