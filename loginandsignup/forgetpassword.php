<?
include "dbconn.php";
session_start();
$email ="";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    // echo "$email";

    $sql = "SELECT * FROM SIGNUP WHERE email = '$email'";
    $result = $conn->query($sql);
    if(!$result){
        echo "succes to fetch data" . $conn->error;
    }
    $data = $result->fetch_assoc();
    if(!$data){
        echo "failed to fecth data". $conn->error;
    }
    // echo $data['EMAIL'];
    if($data && ($data['EMAIL']===$email)){
        $_SESSION['email'] = $data['EMAIL'];
        $_SESSION['password'] = $data['PASSWORD'];
        // echo $_SESSION['email'];
        echo "<script>window.location.href = 'otpgenerate.php'</script>";
    }else{
        echo"<script>alert('no record found with this email')</script>";
        exit();
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
        <h5>enter your email to reset your password</h5>
        email : <input type="email" name="email" placeholder="enter email here" required>
        submit : <input type="submit" name="submit">
        <!-- <a href="otpverify.php">SUBMIT</a> -->
    </form>
</body>
</html>