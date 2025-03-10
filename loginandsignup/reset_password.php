<?
include "dbconn.php";
session_start();
$email_session = $_SESSION['email'];
$password_session = $_SESSION['password'];
// echo $email_session;
// echo $password_session;



$new_password = $cnf_password = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null;
    $cnf_password = isset($_POST['cnf_password']) ? $_POST['cnf_password'] : null;

    if ($new_password && $cnf_password === $password_session) {
        echo "<script>alert('make a unique password that never been used')</script>";
    } elseif ($new_password !== $cnf_password) {
        echo "<script>alert('password do not matched re-enter password')</script>";
    } else {


        $sql_update_password = "UPDATE signup SET password='$new_password' WHERE email='$email_session'";


        if ($conn->query($sql_update_password) === TRUE) {
            echo "<script>alert('password reset successfully');window.location.href='login.html'; </script>";
            echo "<script>window.location.href='logout.php'; </script>";
        } else {
            echo "reset failed: " . $conn->error;
        }











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
    <h1>reset password page</h1>
    <form action="" method="post">
        enter new password : - <input type="text" name="new_password" placeholder="enter new password">
        confirm_password : - <input type="text" name="cnf_password" placeholder="confirm your new password">
        submit :- <input type="submit" name="submit">
    </form>
</body>

</html>