<?php
include 'db_connect.php'; // Include database connection

// Check if database exists
if (!$conn->select_db("crud")) {
    die("Database selection failed: " . $conn->error);
}


// ------------------------------------------------fetching data--------------------------------->
$sql = "SELECT * FROM datatable";
$result = $conn->query($sql);

//---------------------------------------------deleting the data------------------------------------->
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id_del = $_POST['iddelete'];

    if ($id_del > 0) {
        $sql_del = "DELETE FROM datatable WHERE id = $id_del";
        if ($conn->query($sql_del)) {
            echo "<script>alert('Your data was deleted successfully')
            window.location.href = 'thankyou.html';
            </script>";
        } else {
            die("Query failed: " . $conn->error);
        }
    }
}

// Close connection
$conn->close();
?>
<!----------------------------------------html to data in the form of table ------------------------>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View & Delete Data</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>

    <h2>Database Records</h2>
    <table>
        <th>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Mobile No</th>
                <th>Roll No</th>
                <th>City</th>
                <th>Action</th>
            </tr>
        </th>
        <tb>
            <!---------------------- fetching data from the database---------------------------------->
            <?php while ($i = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $i['id']; ?></td>
                    <td><?php echo $i['name']; ?></td>
                    <td><?php echo $i['mobileno']; ?></td>
                    <td><?php echo $i['rollno']; ?></td>
                    <td><?php echo $i['city']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="iddelete" value="<?php echo $i['id']; ?>">
                            <button type="submit" name="delete">Delete</button>
                            <a href="update.php?id=<?php echo $i['id']; ?>"> EDIT </a>
                            
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tb>
    </table>

</body>

</html>