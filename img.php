<?php
 $conn = mysqli_connect('localhost','root','','practice',3307);
?>
<html>
  <head>
    <title>Image Store</title>
</head>
<body>
  <form method="POST" enctype = "multipart/form-data">
    <input type = "file" name="image">
    <input type="submit" name="submit" value="save">
  </form>
  <?php
  if(isset($_POST['submit']))
  {
      if(getimagesize($_FILES['image']['tmp_name'])==false)
      {
        echo "Please select image";
      }
      else {
        $image = $_FILES['image']['tmp_name'];
        $name = $_FILES['image']['name'];

        $image = file_get_contents($image);
        $image = base64_encode($image);

        $sql = "INSERT INTO image_store(name,image) VALUES('$name','$image')";

        if(mysqli_query($conn,$sql))
        {
          echo "Image stored";
        }
        else {
          echo "error";
        }
      }

      $sql = "SELECT * FROM image_store";
      $result = mysqli_query($conn,$sql);
      $images = mysqli_fetch_all($result,MYSQLI_ASSOC);
      print_r($images);
      foreach($images as $imge)
      {
        echo "<img width='300px' height='300px' src='data:image/*;base64,{$imge['image']}'
        alt='img'>";
      }

  }
  else {
    echo "Please select";
  }
   ?>
</body>
</html>
