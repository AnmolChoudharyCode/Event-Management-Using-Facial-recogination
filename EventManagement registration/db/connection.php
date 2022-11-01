<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "eventmanagement";

    $conn = mysqli_connect($servername,$username,$password,$db);

    if($conn){
        // echo "Connection Successful";
    }else{
        die("Connection Failed Error : ".mysqli_connect_error());
    }

?>
