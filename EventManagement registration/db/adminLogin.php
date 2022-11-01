<?php

    include("connection.php");
    $username = $_POST['username-admin'];
    $password = $_POST['password-admin'];
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";

    if(mysqli_query($conn,$query)){
        $data = mysqli_query($conn,$query);
        $total = mysqli_num_rows($data);

        if($total>0){
            session_start();
            $_SESSION['admin'] = $username;
            echo "success";
        }else{
            echo "fail";
        }
    } else{
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }


?>