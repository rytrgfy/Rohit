<?
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include "dbconn.php";
$email = $password = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // for getting id query 
    $fetch_id = "SELECT ID FROM `signup` WHERE email = '$email'";
    $get_id = $conn->query($fetch_id);
    $id_data = $get_id->fetch_assoc();

    // based on id from email i'm passing value to see
    $fetch_data_sql = "SELECT * FROM signup WHERE id = '{$id_data['ID']}' ";
    $result = $conn->query($fetch_data_sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $data = $result->fetch_assoc();

    if (!$data) {
        die("No record found with ");
    }

    if ($data['EMAIL'] === $email && $data['PASSWORD'] === $password) {
        echo "<script>alert('login success')</script>";
    } else {
        echo "<script>alert('Login failed! Use correct email or password.');window.location.href = 'login.html';</script>";

    }


}
?>