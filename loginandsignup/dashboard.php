<?php
session_start();
include "dbconn.php";
// Start session 

if (!isset($_SESSION['user_id'])) {
    header("Location:  index.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['username'];


$sql = "SELECT * FROM signup WHERE id = $user_id";
$result = $conn->query($sql);
// $id_data = $result->fetch_assoc();






?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .file {
            width: 300px;
            height: 300px;
            /* border-radius: 50%; */
            border: 2px solid #ddd;
        }
    </style>
</head>

<body>
    <?php


    while ($row = $result->fetch_assoc()) { ?>
        <?php if (!empty($row['reference_file'])): ?>
            <img src="file_uploads_data/<?php echo $row['reference_file']; ?>" class="file" alt="file file">
        <?php endif; ?>
        <h2>your unique id = <? echo $row['id']; ?> </h2>
        <h2>your name = <? echo $row['name']; ?> </h2>
        <h2>your contact = <? echo $row['contact']; ?> </h2>
        <h2>your address = <? echo $row['address']; ?> </h2>
        <h2>your username = <? echo $row['username']; ?> </h2>
        <h2>your password what you have saved = <? echo $row['password']; ?> </h2>

    <? } ?>


    <a href="logout.php">Logout</a>
</body>

</html>