<?php
session_start();
include "dbconn.php";
$username = $password = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;


    //check  






    // for getting id query 
    $fetch_id = "SELECT id FROM `signup` WHERE username = '$username'";
    $get_id = $conn->query($fetch_id);
    $id_data = $get_id->fetch_assoc();

    // based on id from email i'm passing value to see

    $fetch_data_sql = "SELECT * FROM signup WHERE id = '{$id_data['id']}' ";
    $result = $conn->query($fetch_data_sql);





    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $data = $result->fetch_assoc();

    // if (!$data) {
    //     die("No record found with ");
    // }



    // echo "Email from DB: " . $data['EMAIL'] . "<br>";
    // echo "Password from DB: " . $data['PASSWORD'] . "<br>";
    // echo "Entered Password: " . $password . "<br>";
    // exit();






    if ($data['username'] === $username && $data['password'] === $password) {
        $_SESSION['user_id'] = $data['id']; // Store user ID in session
        $_SESSION['username'] = $data['username'];
        if ($username === 'Admin' && $password === 'Admin') {
            echo "
            <script>
            alert('Login success');
            window.location.href = 'admin.php'; 
            </script>";
            exit();
        } else {
            echo "
            <script>
            alert('Login success');
            window.location.href = 'dashboard.php'; 
            </script>";
            exit();
        }

    } else {
        echo "<script>alert('Login failed! Use correct username or password try signup.');window.location.href = 'index.php';</script>";
        exit();
    }


}
?>