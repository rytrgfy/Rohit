<?php
include "dbconn.php";

// getting data from the name 
//     echo $_POST["name"];
//     echo $_POST["email"];
//     echo $_POST["password"];
//     echo $_POST["cnfpassword"];

/* create table query

    CREATE TABLE signup (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    EMAIL VARCHAR(100) UNIQUE,
    PASSWORD VARCHAR(50),
    CNFPASSWORD VARCHAR(50)  
    );"

*/
$name = $email = $password = $cnfpassword = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email =  $_POST["email"];
    $password  =$_POST["password"];
    $cnfpassword  = $_POST["cnfpassword"];
    
    if ($password !== $cnfpassword) {
        echo "Passwords do not match!";
        exit();
    }


    $insert_sql = "INSERT INTO signup(NAME , EMAIL , PASSWORD ) VALUES( '$name' , '$email' , '$password' ) ";
    if ($conn->query($insert_sql) == TRUE) {
        echo "<script>alert('signup successfull');window.location.href = 'login.html';</script>";
    }else{
        echo "<script>alert('signup failed');window.location.href = 'signup.html';</script>";
        // echo $conn->error;
    }
}