<?php
    include("connection.php");

    $uid= $_POST['uid'];
    $dir = "../dataset/user/".$uid;

    if(is_dir($dir) == false){
        mkdir($dir, 0777, true);
    }

    $fileName = uniqid().".jpg";
    $fileLocation = $dir."/".$fileName;
    move_uploaded_file($_FILES["upload-photo"]["tmp_name"],$fileLocation);
    
    echo "success";
    

?>
