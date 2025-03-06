<?
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


    $insert_sql = "INSERT INTO signup(NAME , EMAIL , PASSWORD , CNFPASSWORD) VALUES( '$name' , '$email' , '$password' ,'$cnfpassword') ";
    if ($conn->query($insert_sql) == TRUE) {
        echo "query inserted successfully";
    }else{
        echo "failed to insert data";
    }
}