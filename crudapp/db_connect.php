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

if (!$conn->select_db("crud")) {
    die("Database selection failed: <br><br>" . $conn->error);
}




?>