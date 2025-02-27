<?
include 'db_connect.php'; // Including the database connection

$name = $mobileno = $rollno = $city = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $mobileno = $_POST["tel"];
    $rollno = $_POST["roll-no"];
    $city = $_POST["city"];
}
// echo "$name " . "<br>";
// echo "$mobile " . "<br>";
// echo "$rollno " . "<br>";
// echo "$city " . "<br>";






// <-------------------------------select database---------------------------->
if (!$conn->select_db("crud")) {
    die("Database selection failed: <br><br>" . $conn->error);
}

//--------------------------------sql connection and input-------------------------------------->


//--------------------------------create database and sql input--------------------------------->






// $sql_insert_val = "INSERT INTO myinfo (id, name, city, mobileno) VALUES ($id, '$name', '$city', '$mobileno')";


// <<--------------------------------------for creating a database------------------------------------>


// $sql_create_db = "create database crud";
// if ($conn->query($sql_create_db) === TRUE) {
//     echo "Database created or already exists<br>";
// } else {
//     die("Failed to create database: <br>" . $conn->error);
// }

// <---------------------------------------------create table in database--------------------------------------->

// $sql_create_table = "CREATE TABLE  datatable (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(255) NOT NULL,
//     mobileno VARCHAR(15) NOT NULL,
//     rollno VARCHAR(50) NOT NULL,
//     city VARCHAR(100) NOT NULL
// )";

// // $count = 0;
// if ($conn->query($sql_create_table) === TRUE) {

//     // if ($count == 0) {
//     echo "<script>alert('created table  successfully');</script>";
//     //     $count++;
//     //     die("data submitted");
//     // }

// } else {
//     die("Failed to insert data: <br>" . $conn->error);
// }


//<--------------------------------------------- insert into table---------------------------------------------------->


$sql_insert = "INSERT INTO datatable (name, mobileno, rollno, city) VALUES ('$name', '$mobileno', '$rollno', '$city')";

if ($conn->query($sql_insert) === TRUE) {
    echo "<script>alert('Form submitted successfully');</script>";
} else {
    die("Failed to insert data: <br>" . $conn->error);
}



















$conn->close();
if (basename(__FILE__) != basename($_SERVER["PHP_SELF"])) {
    function getFormData() {
        return [
            'name' => $_POST['name'] ?? '',
            'mobileno' => $_POST['mobileno'] ?? '',
            'rollno' => $_POST['rollno'] ?? '',
            'city' => $_POST['city'] ?? ''
        ];
    }
    return getFormData();
}





?>

<!----------------------------------html file to update and submit ----------------------------->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update</title>
</head>

<body>
    <h5> if every thing is alright then click submit or else click to update</h5>

    <form action="thankyou.html">
        Thankyou : <input type="submit">
    </form>
    view data: <a href="../crudapp/viewdata.php"> view all data</a>
    <br><br><br>
    <h1> i want to update my data</h1>
    <form action="update.html">
        update data : <input type="submit">
    </form>

</body>

</html>