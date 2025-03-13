<?php
include "dbconn.php";
// $conn = mysqli_connect("localhost", "root", "", "siepl") or die("Connection failed");

if ($_POST['type'] == "state") {
    $sql = "SELECT * FROM states";
    $query = mysqli_query($conn, $sql) or die("Query Unsuccessful.");

    while ($row = mysqli_fetch_assoc($query)) {
        $str .= "<option value='{$row['id']}'>{$row['state_name']}</option>";
    }
} else if ($_POST['type'] == "district") {
    // Fetch districts based on selected state
    $sql = "SELECT * FROM state_details WHERE state_id = {$_POST['id']}";
    $query = mysqli_query($conn, $sql) or die("Query Unsuccessful.");


	$str = "<option value=''>Select District</option>"; // Default option
    while ($row = mysqli_fetch_assoc($query)) {
        $str .= "<option value='{$row['id']}'>{$row['district_name']}</option>";
    }
}

echo $str;
?>
