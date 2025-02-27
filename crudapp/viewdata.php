<?php
include 'db_connect.php'; // Include database connection
// --------------------------------------------------------view data in in format---------------------------------------->
$id = "";
$data = "";
if (!$conn->select_db("crud")) {
    die("Database selection failed: <br><br>" . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    if ($id > 0) {
        // Query to fetch data based on ID
        $sql = "SELECT * FROM datatable WHERE id = $id";
        $result = $conn->query($sql);

        if (!$result) {
            die("Query failed: " . $conn->error); // Debugging output
        } else {
            while ($row = $result->fetch_assoc()) {
                $data = "Name: " . ($row['name']) . "<br>" .
                    "Mobile No: " . ($row['mobileno']) . "<br>" .
                    "Roll No: " . ($row['rollno']) . "<br>" .
                    "City: " . ($row['city']) . "<br>";
            }
        }


    } else {
        $data = " enter a valid ID.";
    }
}
// -------------------------------------------------------delete data ------------------------------------->

$id_del = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['iddelete'])) {
    $id_del = isset($_POST['iddelete']) ? $_POST['iddelete'] : "";


    if ($id_del > 0) {
        // Query to delete data from database;
        $sql_del = "DELETE FROM datatable WHERE id = $id_del";
        $del_result = $conn->query($sql_del);

        if ($conn->affected_rows > 0) {
            echo "<script>alert('Your data was deleted successfully');</script>";
        } else {
            echo "<script>alert('No record found with the given ID');</script>";
        }
        

    } else {
        $data = " enter a valid ID.";
    }








}














// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Data</title>
</head>

<body>

    <h2>Enter ID to View Data</h2>
    <form method="POST" action="">
        <label for="id">Enter ID:</label>
        <input type="number" name="id" required>
        <button type="submit">Submit</button>

    </form>
    
    <h3>Result:</h3>
    <p><?php echo $data; ?></p>
    <form method="POST" action="" >
        delete your data: <input type="number" name="iddelete" required>
        <button type="submit" name="delete">Delete</button>

    </form>

</body>

</html>