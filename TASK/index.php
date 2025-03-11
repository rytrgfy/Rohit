<?php
include "dbconn.php";
session_start();

$sql_query_get_data = "SELECT * FROM task1";
$result = $conn->query($sql_query_get_data);
if (!$result) {
    echo "failedto fetch data" . $conn->error;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['iddelete'])) {
    $id = intval($_POST['iddelete']);
    $sql_delete_query = "DELETE FROM task1 WHERE id = $id";

    if ($conn->query($sql_delete_query) === TRUE) {
        echo "User deleted successfully";
        header("Location: index.php");
        exit();
    } else {
        echo "Delete failed: " . $conn->error;
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view-data</title>
    <style>
        .profile-img {
            width: 50px;
            height: 70px;
            /* border-radius: 50%; */
            border: 2px solid #ddd;
        }

        a {
            color: aliceblue;
        }

        body {
            background-color: rgb(137, 171, 221);
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }

        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            color: wheat;
        }

        th {
            background: rgb(36, 35, 35);
        }

        button {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        a {
            margin-left: 5px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>

<body>



    <h2>Database Records</h2>



    <table style="border: 2px solid black">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Mobile No</th>
                <th>E-mail</th>
                <th>Address</th>
                <th>Age</th>
                <th>photo</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                $num = 1; ?>

                <?php while ($i = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $num++; ?></td>
                        <td><?php echo $i['name']; ?></td>
                        <td><?php echo $i['mobile']; ?></td>
                        <td><?php echo $i['email']; ?></td>
                        <td><?php echo $i['address']; ?></td>
                        <td><?php echo $i['age']; ?></td>
                        <td><?php if (!empty($i['photo'])): ?>
                                <img src="uploads/<?php echo $i['photo']; ?>" class="profile-img" alt="Profile Photo">
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="iddelete" value="<?php echo $i['id']; ?>">
                                <button type="submit" name="delete">Delete</button>
                            </form>
                            <br>
                            <a href="registration.php?id=<?php echo $i['id']; ?>">--EDIT--</a>
                            <br>
                            <br>
                            <a href="details.php?id=<?php echo $i['id']; ?>">View Data</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No data available.</td>
                </tr>
            <?php } ?>
        </tbody>

    </table>


    <a href="registration.php">Enter new data</a>


</body>

</html>