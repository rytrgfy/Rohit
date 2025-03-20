<?php
include "dbconn.php";

if (isset($_POST['district_id'])) {
    $district_id = (int) $_POST['district_id'];

    $sql = "SELECT * FROM city WHERE district_id = $district_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "not exists";
    }
}
?>
