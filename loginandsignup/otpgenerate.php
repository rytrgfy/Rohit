<?
include "dbconn.php";
include "otpgenerate.php";
session_start();
$email = $_SESSION['email'];
$pass = $_SESSION['password'];
echo $email;






//otp generatipon
function generateOTP($length = 6) {
    return str_pad(mt_rand(0, 999999), $length, '0', STR_PAD_LEFT);
}
// $otp = generateOTP(); // Generate OTP
// $_SESSION['otp'] = $otp; // Store OTP in session
if($otp){
    echo "<script>alert('your otp is $otp');</script>";
    echo "<script>window.location.href='otp.html';</script>";
    exit();
    
}


$otpinput ="";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $otpinput = isset($_POST['otpinput']) ? $_POST['otpinput'] : null;
    if($otpinput != $otp){
        echo "invalid otp";
        exit();
    }else{
        echo "otp matched";
    }
}









//otp for validation










?>

<!-- <!DOCTYPE html>
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
</html> -->
