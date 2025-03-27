<?php
include "dbconn.php";
session_start();

// Updated SQL JOIN query to include cities

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != '78') {
    header("Location: 404.php");
    exit();
}

$sql_join_operation = "SELECT
    s.id AS state_id,
    s.state_name,
    d.id AS district_id,
    d.district_name,
    c.id AS city_id,
    c.city_name
FROM state s
LEFT JOIN district d ON s.id = d.state_id
LEFT JOIN city c ON d.id = c.district_id
ORDER BY s.state_name, d.district_name, c.city_name;";

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
    <style>
        body {
            background-color: #f4f4f4;
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
            text-decoration: none;
        }

        a:hover {
            background: #0056b3;
        }

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
        <a href="logout.php"> logout</a>
        <h2>Listing Page</h2>
        <a href="addstate.php">Add a State</a>
        <a href="addDistict.php">Add a District</a>
        <a href="addDetails.php">Add a City</a> <!-- Added link to add city -->
        <a href="addboard.php">Add a BOARDS</a> <!-- Added link to add city -->
    </div>

    <table>
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>State Available</th>
                <th>District Available</th>
                <th>City Available</th> <!-- Changed column header -->
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) { ?>
                <?php
                $num = 1;
                $data = [];

                // Organize data by state and district
                while ($row = $result->fetch_assoc()) {
                    $data[$row['state_name']][$row['district_name']][] = $row;
                }
                ?>

                <?php foreach ($data as $state => $districts) { ?>
                    <?php
                    $state_rowspan = 0;
                    // Calculate total rows for this state
                    foreach ($districts as $district => $cities) {
                        $state_rowspan += count($cities);
                    }
                    $first_state = true;
                    ?>

                    <?php foreach ($districts as $district => $cities) { ?>
                        <?php
                        $district_rowspan = count($cities);
                        $first_district = true;
                        ?>

                        <?php foreach ($cities as $index => $city) { ?>
                            <tr>
                                <?php if ($first_state && $first_district && $index === 0) { ?>
                                    <td rowspan="<?php echo $state_rowspan; ?>"><?php echo $num++; ?></td>
                                    <td rowspan="<?php echo $state_rowspan; ?>"><?php echo $state; ?></td>
                                    <?php $first_state = false; ?>
                                <?php } ?>

                                <?php if ($first_district) { ?>
                                    <td rowspan="<?php echo $district_rowspan; ?>">
                                        <?php echo !empty($district) ? $district : '<span style="color:red;">N/A</span>'; ?></td>
                                    <?php $first_district = false; ?>
                                <?php } ?>

                                <td><?php echo !empty($city['city_name']) ? $city['city_name'] : '<span style="color:red;">No city available</span>'; ?>
                                </td>
                            </tr>
                        <?php } ?>
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