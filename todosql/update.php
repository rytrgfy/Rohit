<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todosql";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if (!$id || !is_numeric($id)) {
    die("Invalid ID provided.");
}

if (!$conn->select_db("todosql")) {
    die("Database selection failed: " . $conn->error);
}

$sql = "SELECT * FROM todo WHERE id = $id";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$data = $result->fetch_assoc();

if (!$data) {
    die("No record found with ID: $id");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $details = $_POST["details"];

    $sql_update = "UPDATE todo SET title='$title', details='$details' WHERE id=$id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Updated successfully');window.location.href='index.php';</script>";
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
    <title>Update Todo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Update Todo</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo $data['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="details">Details:</label>
                <input type="text" name="details" id="details" value="<?php echo $data['details']; ?>" required>
            </div>
            <button type="submit" class="submit-btn">Update</button>
        </form>
    </div>
</body>

</html>
