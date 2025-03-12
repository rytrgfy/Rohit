<?php
include "dbconn.php";

$sql_get_state = "SELECT * FROM states ORDER BY id ASC";
$res = $conn->query($sql_get_state);
if (!$res) {
    echo 'Error: ' . $conn->error;
}


$state = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $state = strtolower($_POST['state']);


    $sql = "INSERT INTO states (state_name) VALUES ('$state');";
    if (!$conn->query($sql)) {
        echo $conn->error;
    } else {
        echo "<script>alert('state added successfully');window.location.href='addstate.php';</script>";
        $state = "";
    }





}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add State</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
        }

        input {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 80%;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add State</h2>
        <form action="" method="post">
            <label for="state">State Name:</label><br>
            <input type="text" id="state" name="state" required><br>
            <button type="submit">Submit</button>
        </form>
    </div>


    <table style="border: 2px solid black">
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>State</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($res && $res->num_rows > 0) {
                $num = 1; ?>

                <?php while ($i = $res->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $num++; ?></td>
                        <td><?php echo $i['state_name']; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No data available.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>




</body>

</html>