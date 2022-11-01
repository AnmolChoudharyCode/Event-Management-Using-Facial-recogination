<?php
    include("connection.php");

    $eDate = $_POST['event-date'];
    $eTitle = $_POST['event-title'];
    $eDescription = $_POST['event-description'];
    $eVenue = $_POST['event-venue'];

    $query = "INSERT INTO events (date, title, description, venue) VALUES ( 
        '$eDate',
        '$eTitle',
        '$eDescription',
        '$eVenue')";

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
