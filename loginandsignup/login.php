<?php
session_start();
include "dbconn.php";
$username = $password = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Fetch user details based on username
    $fetch_user_sql = "SELECT * FROM `signup` WHERE username = '$username'";
    $result = $conn->query($fetch_user_sql);

    // Check if query failed
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $data = $result->fetch_assoc();

    // Check if user exists
    if (!$data) {
        echo "<script>alert('Login failed! Invalid username or password. Try sign up.'); window.location.href = 'index.html';</script>";
        exit();
    }

    // Admin login (No MD5 hashing)
    if ($username === 'Admin' && $password === 'Admin123') {
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['username'] = $data['username'];

        echo "<script>
            alert('Admin Login success');
            window.location.href = 'admin.php'; 
        </script>";
        exit();
    }

    // Regular user login (MD5 password verification)
    if ($data['password'] === md5($password)) {
        $_SESSION['user_id'] = $data['id']; // Store user ID in session
        $_SESSION['username'] = $data['username'];

        echo "<script>
            alert('Login success');
            window.location.href = 'dashboard.php'; 
        </script>";
        exit();
    } else {
        echo "<script>alert('Login failed! Invalid username or password. Try signing up.'); window.location.href = 'index.html';</script>";
        exit();
    }
}
?>
