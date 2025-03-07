<?
session_start();
include "dbconn.php";
$email = $password = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;


    //check  
    





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

    // if (!$data) {
    //     die("No record found with ");
    // }



    // echo "Email from DB: " . $data['EMAIL'] . "<br>";
    // echo "Password from DB: " . $data['PASSWORD'] . "<br>";
    // echo "Entered Password: " . $password . "<br>";
    // exit();






    if ($data['EMAIL'] === $email && $data['PASSWORD'] === $password) {
        $_SESSION['user_id'] = $data['ID']; // Store user ID in session
        $_SESSION['email'] = $data['EMAIL'];
        echo "
        <script>
        alert('Login success');
        window.location.href = 'dashboard.php'; 
        </script>";
        exit();
    } else {
        echo "<script>alert('Login failed! Use correct email or password try signup.');window.location.href = 'login.html';</script>";
        exit();
    }


}
?>