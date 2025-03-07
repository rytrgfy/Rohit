<?
include "dbconn.php";
include "otpgenerate.php";
session_start();
$email = $_SESSION['email'];
$pass = $_SESSION['password'];
echo $email;
// echo $pass;


//otp for validation


$otpinput ="";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $otpinput = isset($_POST['otpinput']) ? $_POST['otpinput'] : null;
    if($otpinput != $otp){
        echo "<script>alert('invalid otp reenter otp');</script>";
    }else{
        echo "otp matched";
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
        enter otp : <input type="number" name="otpinput" placeholder="enter otp">
        submit : <input type="submit" name="submit">
    </form>
</body>
</html>
