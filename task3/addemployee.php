<?php
include "dbconn.php";

$addemployee = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $addemployee = $_POST['addemployee'];
}


$sql = "INSERT INTO emp_name (employeename) VALUES ('$addemployee')";
if (!$conn->query($sql)) {
    echo "<script>alert('Error: " . $conn->error . "');</script>";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add-Employee</title>
</head>

<body>
    <form action="" method="POST">
        Add Employee : <input type="text" name="addemployee" placeholder="ADD A Employee" required>
        submit : <input type="submit" name="submit" id="submit">
    </form>

    <a href="assigntask.php">assigntask</a> <br>
    <a href="addemployee.php">add employee</a> <br>
    <a href="index.php"> index</a>
</body>

</html>