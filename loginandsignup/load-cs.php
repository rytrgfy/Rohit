<?php
include "dbconn.php";

$type = $_POST['type'];

if ($type == "state") {
    $sql = "SELECT * FROM state";
    $query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['state_name']}</option>";
    }
} 
elseif ($type == "district") {
    $sql = "SELECT * FROM district WHERE state_id = {$_POST['id']}";
    $query = mysqli_query($conn, $sql);
    echo "<option value=''>Select District</option>";
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['district_name']}</option>";
    }
}
elseif ($type == "city") {
    $sql = "SELECT * FROM city WHERE district_id = {$_POST['id']}";
    $query = mysqli_query($conn, $sql);
    echo "<option value=''>Select City</option>";
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['city_name']}</option>";
    }
}
?>
