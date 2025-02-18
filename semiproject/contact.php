
<?php


$con = mysqli_connect('localhost', 'root', '', 'studentdatabase');

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve POST data
$txtUsername = isset($_POST['txtUsername']) ? $_POST['txtUsername'] : '';
$txtUserId = isset($_POST['txtUserId']) ? $_POST['txtUserId'] : '';
$txtEmail = isset($_POST['txtEmail']) ? $_POST['txtEmail'] : '';
$txtPhone = isset($_POST['txtPhone']) ? $_POST['txtPhone'] : '';
$txtPassword = isset($_POST['txtPassword']) ? $_POST['txtPassword'] : '';

// Prepare the SQL statement
$sql = "INSERT INTO `studentdatabase` (`username`, `user_id`, `email`, `phoneNumber`, `password`) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($con, $sql);

if ($stmt === false) {
    die("Error preparing statement: " . mysqli_error($con));
}

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "sssss", $txtUsername, $txtUserId, $txtEmail, $txtPhone, $txtPassword);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    header("Location: home.html");  
    exit();
    
} else {
    echo "Error: " . mysqli_error($con);
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($con);





?>