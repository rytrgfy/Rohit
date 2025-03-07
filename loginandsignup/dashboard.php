<?
session_start();
include "dbconn.php";
// Start session 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];


$sql = "SELECT * FROM signup WHERE ID = $user_id";
$result = $conn->query($sql);
// $id_data = $result->fetch_assoc();






?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?

    while ($row = $result->fetch_assoc()) { ?>
        <h2>your unique id = <? echo $row['ID']; ?> </h2>
        <h2>your name = <? echo $row['NAME']; ?> </h2>
        <h2>your email = <? echo $row['EMAIL']; ?> </h2>
        <h2>your password what you have saved = <? echo $row['PASSWORD']; ?> </h2>

        <form method="POST" action="delete.php">
            <input type="hidden" name="iddelete" value="<?php echo $row['ID']; ?>">
            <button type="submit" name="delete">Delete</button>
            <a href="update.php?ID=<?php echo $row['ID']; ?>"> EDIT </a>

        </form>
    <? } ?>


    <a href="logout.php">Logout</a>
</body>

</html>
