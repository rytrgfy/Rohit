<?php
include "dbconn.php";


$name = $contact = $address = $reference_file = $username = $password = $cnfpassword = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $contact = $_POST['contact'];
    $address = $_POST['address'];


    if (!empty($_FILES['reference_file']['name'])) {
        $file_name = $_FILES['reference_file']['name'];  // Get file name
        $tmp_name = $_FILES['reference_file']['tmp_name']; // Get temporary file path
        $target_path = "file_uploads_data/" . $file_name; // Define upload path

        // Move the uploaded file to the target directory
        if (move_uploaded_file($tmp_name, $target_path)) {
            $reference_file = $file_name; // Assign the file name for database insertion
        } else {
            echo "File upload failed!";
            exit();
        }
    } else {
        echo "No file selected.";
        exit();
    }

    $username = $_POST["username"];
    $password = $_POST["password"];
    $cnfpassword = $_POST["cnfpassword"];






    if ($password !== $cnfpassword) {
        echo "Passwords do not match!";
        exit();
    }


    $insert_sql = "INSERT INTO signup (name, contact, address, reference_file, username, password) 
               VALUES ('$name', '$contact', '$address', '$reference_file', '$username', '$password')";

    if ($conn->query($insert_sql) == TRUE) {
        echo "<script>alert('signup successfull');window.location.href = 'index.html';</script>";
    } else {
        // echo "<script>alert('signup failed');window.location.href = 'signup.html';</script>";
        echo $conn->error;
    }

    // print_r($_FILES['reference_file']);





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
    <form action="" method="post" enctype="multipart/form-data">
        Name : <input type="text" name="name" placeholder="enter your name">
        contact : <input type="tel" name="contact" placeholder="enter your mobile number">
        address : <textarea name="address" id="address" cols="9" rows="5"></textarea>
        select state : <select name="state" id="select">--state to be added dynamically and city as well--</select>
        Resume/Reference_file : <input type="file" name="reference_file">



        <h1>Academic area</h1>
        <br>

        Board :
        <select name="board">
            <option value="cbse">CBSE</option>
            <option value="icse">ICSE</option>
            <option value="state">State Board</option>
            <option value="other">Other</option>
        </select>

        Courses : <input type="text" name="courses" placeholder="Enter your courses">
        Total Marks : <input type="number" name="total_marks" placeholder="Enter total marks">
        Marks Secured : <input type="number" name="secured_marks" placeholder="Enter marks secured">
        total percentage : <input type="text" name="percentage" value="ADDED Dynamically">

        Profile Photo : <input type="file" name="profile_photo" accept="image/*">


        <h1>login details</h1>
        <br><br>
        username : <input type="text" name="username" placeholder="enter your username">
        password : <input type="text" name="password" placeholder="enter your password">
        confirm password : <input type="text" name="cnfpassword" placeholder="comfirm your password">
        signup : <input type="submit" name="signup">
    </form>
</body>

</html>