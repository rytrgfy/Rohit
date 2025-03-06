<?
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loginform";

$conn = new mysqli($servername , $username,$password ,$dbname);

if($conn->connect_error){
    die ("failed to create database"  . "<br>" .$conn->connect_error);
}

if(!$conn->select_db("loginform")){
    die ("Database selection failed: <br><br>" . $conn->error);
}

