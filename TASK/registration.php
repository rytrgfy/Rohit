<?php
include "dbconn.php";

$name = $mobile = $email = $address = $age = $gender = $photo = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    // file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
    }

    // echo $name . "<br>";
    // echo $mobile . "<br>";
    // echo $email . "<br>";
    // echo $address . "<br>";
    // echo $age . "<br>";
    // echo $gender . "<br>";
    // echo $photo . "<br>";

    //----------------create db in db------------

    // $sql = "CREATE DATABASE TASK";
    // if ($conn->query($sql) === TRUE) {
    //     echo "Database created successfully";
    // } else {
    //     echo "Error creating database: " . $conn->error;
    // }


    //----------------create table ------


    // $sql_create_table = "CREATE TABLE task1 (
    // id INT AUTO_INCREMENT PRIMARY KEY,
    // name VARCHAR(255) NOT NULL,
    // mobile VARCHAR(15)NOT NULL,
    // email VARCHAR(255) UNIQUE,
    // address TEXT,
    // age INT,
    // gender ENUM('Male', 'Female', 'Other'),
    // photo VARCHAR(255)
    // );";



    // if ($conn->query($sql_create_table) === TRUE) {
    //     echo "table created successfully";
    // } else {
    //     echo "Error creating table: " . $conn->error;
    // }

    




}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>

<body>

    <form action="" method="post" enctype="multipart/form-data">
        <h3>Input Your Data</h3>
        Name: <input type="text" name="name" required value="">
        <br><br>
        Mobile: <input type="tel" name="mobile" required value="">
        <br><br>
        Email: <input type="email" name="email" required value="">
        <br><br>
        Address: <input type="text" name="address" required value="">
        <br><br>
        Age: <input type="number" name="age" required value="">
        <br><br>

        Gender:
        <input type="radio" name="gender" value="male" required> Male
        <input type="radio" name="gender" value="female"> Female
        <input type="radio" name="gender" value="others"> Others
        <br><br>

        Photo: <input type="file" name="photo" required value="">
        <br><br>

        <input type="submit" name="submit" value="Submit">
    </form>

</body>

</html>