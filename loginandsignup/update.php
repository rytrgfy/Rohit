<?
include "dbconn.php";
session_start();
$id = $_SESSION['user_id'];
// echo $id;

$sql = "SELECT * FROM signup where id = $id";
$result = $conn->query($sql);

// if($result){
//     echo "data npt got to result";

// }
$data = $result->fetch_assoc();
if ($data) {
    echo "data fetch succesfully";
}
$id = $data['ID'];
$name = $data['NAME'];
$email = $data['EMAIL'];
$password = $data['PASSWORD'];



$update_name = $update_email = $update_password = $update_cnfpassword = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $update_name = $_POST["name"];
    $update_email = $_POST["email"];
    $update_password = $_POST["password"];
    $update_cnfpassword = $_POST["cnfpassword"];

    if ($update_password !== $update_cnfpassword) {
        echo "<script>alert('Passwords do not match'); window.location.href = 'update.php';</script>";
        exit();
    }

    $sql_update = "UPDATE signup SET name='$update_name', email='$update_email', password='$update_cnfpassword' WHERE id=$id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Updated successfully');window.location.href='dashboard.php';</script>";
    } else {
        echo "Update failed: " . $conn->error;
    }
}























?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update-page</title>
</head>

<body>
    <form action="" method="post">
        Name : <input type="text" name="name" placeholder="enter your name" value="<? echo "$name"; ?>">
        email : <input type="email" name="email" placeholder="enter your email" value="<? echo "$email"; ?>">
        password : <input type="text" name="password" placeholder="enter your password" value="<? echo "$password"; ?>">
        confirm password : <input type="text" name="cnfpassword" placeholder="comfirm your password"
            value="<? echo "$password"; ?>">
        signup : <input type="submit" name="signup">



    </form>

</body>

</html>