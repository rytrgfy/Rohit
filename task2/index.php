<?php
include "dbconn.php";

// Corrected SQL JOIN query
$sql_join_operation = "SELECT s.id AS state_id, s.state_name, sd.district_name, sd.details FROM states s LEFT JOIN state_details sd ON s.id = sd.state_id";

$result = $conn->query($sql_join_operation);
if (!$result) {
    echo "Error: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing Page</title>
</head>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        } */

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 20px;
        }

        a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background:rgb(3, 117, 240);
            color: white;
            border-radius: 5px;
        }

        /* a:hover {
            background: #0056b3;
        } */

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            border: 2px solid black;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color:rgb(0, 0, 0);
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Listing Page</h2>
        <a href="addstate.php">Add a State</a>
        <a href="addDistict.php">Add a District</a>
        <a href="addDetails.php">Add Details</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>State</th>
                <th>District</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                $num = 1;
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $num++; ?></td>
                        <td><?php echo htmlspecialchars($row['state_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['district_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['details']); ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No data available.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>

</html>