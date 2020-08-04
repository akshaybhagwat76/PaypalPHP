<?php
require_once("./config.php");
$script_root = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
if(isset($_COOKIE['file_name'])) {
  if(file_exists($_COOKIE['file_name'])){
    $myFile = $_COOKIE['file_name'];
  }
}else{
  $myFile = "";
}
$errors = false;
if (isset($_POST['submit'])){
//if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
if(isset($_FILES['image']['name'])) {
$target_dir = "uploads/";
$image_name = basename($_FILES["image"]["name"]);
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$uploadOk = 1;

$script_root = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) ."/";
$uploaded_img_url = $script_root .$target_file;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        
        $uploadOk = 1;
    } else {
        
        $uploadOk = 0;
    }

    if(strlen($image_name)==0) {
        $img_error = "Sorry, you have not selected a file!";
        $uploadOk = 0;
        $errors = true;
    }
     else
// Allow certain file formats
if(strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpeg"
&& strtolower($imageFileType) != "gif" ) {
    $img_error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    //$img_error = "A".$image_name."B" ;
    //$img_error = strlen($image_name);
    $uploadOk = 0;
    $errors = true;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {

    $errors = true;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $errors = false;
    } else {
        $errors = true;
    }
}
}
if(!isset($_COOKIE['file_name'])) {
    $myFile = "uploads/" .mt_rand(10000000, 99999999) .".json";
    setcookie("file_name", $myFile, time() + 3600, '/');
} else {
  $myFile = $_COOKIE['file_name'];
}

  try
  {
//Get form data
       $formdata = array(
          'fname'=> $_POST['fname'],
          'lname'=> $_POST['lname'],
          'email'=>$_POST['email'],
          'amount'=> intval($_POST['amount']),
          'coname'=> $_POST['coname'],
          'coabout'=> $_POST['coabout'],
          'address'=> $_POST['address'],
          'img'=> $_POST['img'],
          'logo'=> $_POST['logo'],
          'width'=> $_POST['width'],
          'checkbox'=> $_POST['checkbox'],
          'height'=> $_POST['height'],
          //'charity'=> $_POST['charity'],
       );

       if(isset($uploadOk) && $uploadOk){
          $formdata["image_url"] = $uploaded_img_url;
          $formdata["image_name"] = $image_name;
       }

       $jsondata = json_encode($formdata, JSON_PRETTY_PRINT);
       
       //write json data into data.json file
       if(file_put_contents($myFile, $jsondata)) {
            $file_saved = true;
        }
       else 
            $file_saved = false;

   }
   catch (Exception $e) {
            
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            $errors = true;
   }
}
?>
