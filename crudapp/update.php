<?
include 'db_connect.php';
$id = $_GET['id'];

if (!$id || !is_numeric($id)) {
    die("invalid no id provided till the time");
}


//--------------------------------------getting data in the form of id---------------------------------->


$sql = "SELECT * FROM datatable WHERE id = $id";
$result = $conn->query($sql);


if (!$result) {
    die("Query failed: " . $conn->error);
}

$data = $result->fetch_assoc();

if (!$data) {
    die("No record found with ID: $id");
}

//-----------------------------update the data ------------------------------------------->



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $mobileno = $_POST["tel"];
    $rollno = $_POST["roll-no"];
    $city = $_POST["city"];

    $sql_update = "UPDATE datatable 
                   SET name='$name', mobileno='$mobileno', rollno='$rollno', city='$city' 
                   WHERE id=$id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Updated successfully'); window.location.href='thankyou.html';</script>";
    } else {
        echo "Update failed: " . $conn->error;
    }
}












?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update</title>
</head>

<body>

    <form action="" method="POST">
        <h3>which data you want to update update below</h3>
        name : <input type="text" name="name" value="<?= $data['name']; ?>" required><br><br>
        <br><br>
        mobile : <input type="tel" name="tel" value="<?= $data['mobileno']; ?>" required><br><br>
        <br><br>
        roll-no : <input type="number" name="roll-no" value="<?= $data['rollno']; ?>" required><br><br>
        <br><br>
        city : <input type="text" name="city" value="<?= $data['city']; ?>" required><br><br>
        <br><br>
        submit : <input type="submit" name="update">
        <br><br>
    </form>



</body>

</html>