<?php include 'config.php';?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {


  $target_dir = "../../../assets/site-img/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {

      $displayx = "File is an image - " . $check["mime"] . ".";

      $uploadOk = 1;
    } else {

      $displayx = "File is not an image.";

      $uploadOk = 0;
    }
  }

  // Check if file already exists
  if (file_exists($target_file)) {

    $displayx = "Sorry, file already exists.";

    $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {

    $displayx = "Sorry, your file is too large.";

    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {

    $displayx = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";

    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {

    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  }


  else {



    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {


      $xfilesdata1 = $_FILES["fileToUpload"]["name"];



      $medianame1 = $_POST['x1'];


      $sql = "INSERT INTO fumaco_gallery (medianame, mediaurl, mediafiles)
        VALUES ('$medianame1', 'https://test.fumaco.com.ph/assets/site-img/', '$xfilesdata1')";

        if ($fumaco_conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        header("Location: https://test.fumaco.com.ph/server/admin/v1/blog_view.php?id=$name1&ok=1");
        } else {
        echo "Error deleting record: " . $fumaco_conn->error;
        header("Location: https://test.fumaco.com.ph/server/admin/v1/blog_view.php?id=e$name1&rror=1");
        }



      exit();


      echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";

    }

    else {
    //header("Location: https://test.fumaco.com.ph/server/admin/v1/home-page.php?status=error");
    }
  }


}
?>
