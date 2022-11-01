<?php
    include("connection.php");

    $eid = $_POST['edit-event-id'];
    $eDate = $_POST['edit-event-date'];
    $eTitle = $_POST['edit-event-title'];
    $eDescription = $_POST['edit-event-description'];
    $eVenue = $_POST['edit-event-venue'];

    $query = "UPDATE events SET date = '$eDate',title = '$eTitle', description = '$eDescription', venue = '$eVenue' WHERE eid = '$eid'";

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
