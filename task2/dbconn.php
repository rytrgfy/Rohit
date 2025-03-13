<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TASK2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed:<br> " . $conn->connect_error);
}

if (!$conn->select_db("TASK2")) {
    die("Database selection failed: <br><br>" . $conn->error);
}

?>