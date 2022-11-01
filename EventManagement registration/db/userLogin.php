<?php

    include("connection.php");
    $email = $_POST['email-user'];
    $password = $_POST['password-user'];
    $query = "SELECT * FROM user WHERE email='$email' AND password='$password'";

    if(mysqli_query($conn,$query)){
        $data = mysqli_query($conn,$query);
        $total = mysqli_num_rows($data);

        if($total>0){
            session_start();
            $_SESSION['user'] = $email;
            $resultArray = array();
            while($row =mysqli_fetch_assoc($data))
            {
                $resultArray[] = $row;
            }
            //print_r($resultArray);
            echo json_encode($resultArray);
        }else{
            echo "fail";
        }
    } else{
        echo "error";
    }


?>