<?php
include "dbconn.php";
session_start();






$otpinput = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $otp_sent_to_user = $_SESSION['otp'];
    // echo $otp_sent_to_user;
    $otpinput = isset($_POST['otpinput']) ? $_POST['otpinput'] : null;
    if ($otpinput != $otp_sent_to_user) {
        echo "<script>alert('invalid otp');window.location.href = 'otp.php'</script>;";
        // exit();
    } else {
        echo "otp matched";
        echo "<script>window.location.href = 'reset_password.php'</script>;";
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post">
        enter otp : <input type="number" name="otpinput" placeholder="enter otp" required>
        submit : <input type="submit" name="submit">
    </form>
</body>

</html>