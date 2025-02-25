<?
$id = $name = $city = $mobileno  = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $city = $_POST["city"];
    $mobileno = $_POST["tel"];
}

// echo $id . "<br>";
// echo $name . "<br>";
// echo $city . "<br>";
// echo $mobile . "<br>";  




// db connection 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siepl";

$conn = new mysqli($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} //else{
//     echo "<script>alert('Connected successfully');</script>" ;
// }
// the above line should be uncomment and show the data connection or not;
// db connection only 


 


// create database
if (!$conn->select_db("siepl")) {
    die("Database selection failed: " . $conn->error);
}

// for inserting a data to table
$sql_insert_val = "INSERT INTO myinfo (id, name, city, mobileno) VALUES ($id, '$name', '$city', '$mobileno')";
if ($conn->query($sql_insert_val) == TRUE) {
    echo "<script>alert('data inserted successfully');</script> ";
} else {
    echo "failed to create data base" . $conn->error;
}









// for fetching data from database 
$sql_fetch_data = "select id , name, city , mobileno from myinfo";
$result = $conn->query($sql_fetch_data);
if($result){
    echo "<script> alert('data fetched successfully and stored in results')</script>";
}

if ($conn->query($sql_fetch_data) == TRUE) {
    echo "<script>alert('query successfully fetched');</script> ";
    
    while($row = mysqli_fetch_assoc($result)){
        echo "ID: " . $row['id'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "City: " . $row['city'] . "<br>";
        echo "Mobile: " . $row['mobileno'] . "<br>";
        echo "<hr>";

    }
       
} else {
    echo "failed to create data base" . $conn->error;
}
// fetch complete











$conn->close();
?>