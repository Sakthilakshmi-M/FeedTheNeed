<?php 
  include('config/connect.php');

  if(isset($_GET['id']))
  {
    $id = mysqli_real_escape_string($conn,$_GET['id']);

    $sql = "SELECT * FROM donor WHERE donor_id = $id ";

    $result = mysqli_query($conn,$sql);

    $donorDetails = mysqli_fetch_assoc($result);
    
    

    $sql = "SELECT * FROM food WHERE donor_id = $id";

    $result = mysqli_query($conn,$sql);

    $foodDetails = mysqli_fetch_all($result,MYSQLI_ASSOC);


  }
?>

<html>
  <head>
    <style>
      table,tr,th {
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
  <?php include('templates/header.php'); ?>
  <?php if(isset($donorDetails['donor_name'])) { ?>
    <center>
  <h1><?php echo htmlspecialchars($donorDetails['donor_name']); ?></h1>
  <table>
    <tr>
      <th>Address</th>
      <td > <p><?php echo htmlspecialchars($donorDetails['address']); ?></p></td>
    </tr>
    <tr>
      <th>Mobile Number:</th>
      <td ><p><?php echo htmlspecialchars($donorDetails['phone']); ?></p></td>
    </tr>
    <tr>
      
      <th>Food Item</th>
      <td>Quantity in kgs</td>
    </tr>
    <?php foreach($foodDetails as $foodDetail) { ?>
    <tr>
      
      <th><p><?php echo htmlspecialchars($foodDetail['food_item']); ?></p></th>
      <td><p><?php echo htmlspecialchars($foodDetail['quantity']); ?></p></td>
    </tr>
      <?php } ?>
 
    <tr>
      <th>Preparation Time: </th>
      <td><p><?php echo htmlspecialchars($donorDetails['time']); ?></p></td>
    </tr>
  </table>
  <button class="btn"><a href="order.php?id=<?php echo htmlspecialchars($id);?>">Order Now</a></button>
  </center>

  <?php } ?>
  <?php include('templates/footer.php'); ?>