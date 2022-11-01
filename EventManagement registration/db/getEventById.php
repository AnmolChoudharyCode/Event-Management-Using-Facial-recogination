<?php

    include("connection.php");
    $eid = $_POST['eid'];
    $query = "SELECT * FROM events WHERE eid='$eid'";
 
    if(mysqli_query($conn,$query)){
        $data = mysqli_query($conn,$query);
        $total = mysqli_num_rows($data);

        if($total>0){
            $resultArray = array();
            while($row =mysqli_fetch_assoc($data))
            {
                $resultArray[] = $row;
            }
            echo json_encode($resultArray);
        }else{
            echo "fail";
        }
    } else{
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }


?>