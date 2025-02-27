<?

//<<-------- --------- -------- ------- -------- ----database connection ----------------------------------------------------->>

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crud";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed:<br> " . $conn->connect_error);
}






?>