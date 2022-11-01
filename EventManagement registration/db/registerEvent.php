<?php
    include("connection.php");

    $eid = $_POST['eid'];
    $name = $_POST['participant-name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "INSERT INTO user (name, mobile, email, password, eid) VALUES ( 
        '$name',
        '$mobile',
        '$email',
        '$password',
        '$eid')";

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
