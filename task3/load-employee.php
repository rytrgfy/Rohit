<?php
include 'dbconn.php'; 

// Query to fetch employees
$sql = "SELECT id, employeename FROM emp_name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['employeename'] . '</option>';
    }
} else {
    echo '<option value="">No employees found</option>';
}

// $conn->close();
?>
