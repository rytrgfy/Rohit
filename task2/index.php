<?php
include "dbconn.php";

// Corrected SQL JOIN query
$sql_join_operation = "SELECT s.id AS state_id, s.state_name, sd.district_name, sd.details FROM states s LEFT JOIN state_details sd ON s.id = sd.state_id order by s.state_name";

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
    body {
        background-color: #f4f4f4;
        /* text-align: centergb(175, 175, 175) */
        padding: 50px;
    }

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
        background: rgb(3, 117, 240);
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
        background-color: rgb(0, 0, 0);
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
                <th>State Available</th>
                <th>District Available</th>
                <th>Details Available</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) { ?>
                <?php
                $num = 1;
                $data = [];

                // Fetch data and group by state_name
                while ($row = $result->fetch_assoc()) {
                   
                    $data[$row['state_name']][] = $row;
                }
                ?>

                <?php foreach ($data as $state => $rows) { ?>
                    <?php
                    $rowspan = count($rows);
                    $firstRow = true;
                    ?>

                    <?php foreach ($rows as $row) { ?>
                        <tr>
                            <?php if ($firstRow) { ?>
                                <td rowspan="<?php echo $rowspan; ?>"><?php echo $num++; ?></td>
                                <td rowspan="<?php echo $rowspan; ?>"><?php echo $state; ?></td>
                                <?php $firstRow = false; ?>
                            <?php } ?>

                            <td><?php echo !empty($row['district_name']) ? $row['district_name'] : '<span style="color:red;">N/A</span>'; ?>
                            </td>
                            <td><?php echo !empty($row['details']) ? $row['details'] : '<span style="color:red;">No data available</span>'; ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            <?php } else { ?>
                <tr>
                    <td colspan="4" style="text-align:center; color:red;">No data available.</td>
                </tr>
            <?php } ?>

        </tbody>
    </table>




</body>

</html>