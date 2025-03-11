<?php
include "dbconn.php";
session_start();
$email = $_SESSION['email'];
$pass = $_SESSION['password'];
echo $email;






//otp generatipon
function generateOTP($length = 6) {
    return str_pad(mt_rand(0, 999999), $length, '0', STR_PAD_LEFT);
}

$otp = generateOTP(); // Generate OTP
$_SESSION['otp'] = $otp; // Store OTP in session
if($otp){
    echo "<script>alert('your otp is $otp');</script>";
    echo "<script>window.location.href='otp.php';</script>";
    exit();
    
}

?>

