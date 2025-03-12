<?php
include "dbconn.php";

if (isset($_POST['district_id'])) {
    $district_id = $_POST['district_id'];

    $sql = "SELECT details FROM state_details WHERE id = $district_id";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        echo $row['details']; 
    } else {
        echo "";
    }
}
?>
