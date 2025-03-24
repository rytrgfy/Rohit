<?php
include'dbconn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$id = $_GET['id'];
echo $id;
?>