<?

$title = $details = "";



// echo "$title";
// echo "$details";

// ------------------------------------------------------------
//db connection
//-------------------------------------------------------------

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todosql";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed:<br> " . $conn->connect_error);
}

if (!$conn->select_db("todosql")) {
    die("Database selection failed: <br><br>" . $conn->error);
}



// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $title = isset($_POST['title']) ? $_POST['title'] : "";
//     $details = isset($_POST['details']) ? $_POST['details'] : "";

//     $sql_insert_into_table = "INSERT INTO TODO (title, details) VALUES ('$title' , '$details')";

//     if ($conn->query($sql_insert_into_table) === TRUE) {
//         echo '<script>alert("data inserted successfully")</script>';
//     }
// }





if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['details'])) {
    $title = trim($_POST['title']);
    $details = trim($_POST['details']);

    if (!empty($title) && !empty($details)) {
        // ✅ Escape special characters to prevent SQL injection
        $title = mysqli_real_escape_string($conn, $title);
        $details = mysqli_real_escape_string($conn, $details);

        // ✅ Insert directly without prepared statement
        $sql_insert_into_table = "INSERT INTO TODO (title, details) VALUES ('$title', '$details')";

        if ($conn->query($sql_insert_into_table) === TRUE) {
            echo '<script>alert("Data inserted successfully!");</script>';
            exit();
        } else {
            echo '<script>alert("Error inserting data: ' . $conn->error . '");</script>';
        }
    } else {
        echo '<script>alert("Please fill in all fields!");</script>';
    }
}












/* 
                        create db query

$sql_create_db_query = "create database $dbname";
if($conn->query($sql_create_db_query) === TRUE){
    echo "db created successfully";
}

*/

/*
$sql_create_table_query = "CREATE TABLE todo (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    title TEXT NOT NULL,
    details TEXT NOT NULL
);";

if($conn->query($sql_create_table_query) === TRUE){
    echo "table created successfully";
}else{
    echo "error";
}
*/






//fetch data 

$sql = "SELECT * FROM todo";
$result = $conn->query($sql);

if (!$result) {
    echo "failed to fetch data";
}


//delete query 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id_del = $_POST['iddelete'];

    if ($id_del > 0) {
        $sql_del = "DELETE FROM todo WHERE id = $id_del";
        if ($conn->query($sql_del)) {
            echo "<script>alert('Your data was deleted successfully'); window.location.href='index.php';</script>";
            exit(); // Ensure script stops execution
        } else {
            die("Query failed: " . $conn->error);
        }
    }
}








?>





















<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    Add : <button><a href="add.php">ADD</a></button>


    <!-- retrive data -->


    <?
    while ($i = $result->fetch_assoc()) { ?>
        <div>
            <h2><? echo $i['id']; ?> </h2>
            <h4>
                <? echo $i['title']; ?>
            </h4>
            <p> <? echo $i['details']; ?> </p>
            <div>

                <form method="POST" action="">
                    <input type="hidden" name="iddelete" value="<?php echo $i['id']; ?>">
                    <button type="submit" name="delete">Delete</button>
                    <a href="update.php?id=<?php echo $i['id']; ?>"> EDIT </a>

                </form>
            </div>
        </div>
        <?php
    }
    ?>








    <script src="app.js"></script>
</body>

</html>