<?php
include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $district_id = isset($_POST['district_id']) ? (int) $_POST['district_id'] : 0;

    if ($district_id > 0) {
        $query = "SELECT details FROM state_details WHERE id = $district_id AND details IS NOT NULL AND details <> ''";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            echo "exists"; // Return "exists" if details are found
        } else {
            echo "not_exists"; // Return "not_exists" if details are not found
        }
    } else {
        echo "invalid"; // Return "invalid" if district ID is missing
    }
}
?>
