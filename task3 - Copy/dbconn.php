<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TASK3";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed:<br> " . $conn->connect_error);
}

if (!$conn->select_db("TASK3")) {
    die("Database selection failed: <br><br>" . $conn->error);
}

?>