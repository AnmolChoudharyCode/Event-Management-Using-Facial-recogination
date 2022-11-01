<?php
    include("connection.php");

    $uid = $_POST['uid'];
    $eid = $_POST['eid'];
    $name = $_POST['participant-name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "UPDATE user SET 
        name='$name', 
        mobile='$mobile', 
        email='$email', 
        password='$password', 
        eid ='$eid'
        WHERE uid = '$uid'";

    if(mysqli_query($conn,$query)){
        if(mysqli_affected_rows($conn) > 0){
            echo "success";
        }else{
            echo "fail";
        }
    }else{
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

?>
