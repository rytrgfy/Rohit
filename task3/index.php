<?php
include "dbconn.php";

$sql = "SELECT * FROM taskassign";
$res = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index page</title>
    <style>
        body {
            background-color: antiquewhite;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
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
            width: 100px;
            height: 50px;
            /* margin: 10px ; */
            /* padding: 10px; */
            background: rgb(84, 233, 116);
            color: black;
            border-radius: 5px;
        }

        a:hover {
            background: #0056b3;
            color: white;
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

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>

<body>

    <h1>Task Alloted to employees</h1>
    <table>
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>Task Name</th>
                <th>Task assigned to employeess</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($res->num_rows > 0) {
                $num = 1;
                while ($row = $res->fetch_assoc()) {
                    if (!empty($row['assign_to'])) {
                        $ids = explode(",", $row['assign_to']);
                        $employee_names = [];
                        // print_r($ids);
            
                        foreach ($ids as $arrs) {
                            // explicte converion 
                            $arrs = intval($arrs);

                            $sql_query_to_get_name = "SELECT employeename FROM emp_name WHERE id = $arrs";
                            $result = $conn->query($sql_query_to_get_name);

                            if (!$result) {
                                echo "Failed to get data";
                                continue;
                            }

                            if ($result->num_rows > 0) {
                                while ($names = $result->fetch_assoc()) {
                                    $employee_names[] = $names['employeename'];
                                }
                            }
                        }
                        $employee_names_str = !empty($employee_names) ? implode(", ", $employee_names) : '<span style="color:red;">No Employees Assigned</span>';
                        echo "<tr>
                        <td>{$num}</td>
                        <td>{$row['task_name']}</td>
                        <td>{$employee_names_str}</td>
                      </tr>";
                        $num++;
                    }
                }
            } else { ?>
                <tr>
                    <td colspan="3" class="no-data">No tasks assigned.</td>
                </tr>
            <?php } ?>

        </tbody>
    </table>

    <br><br>
    <a href="assigntask.php">Assign Task</a> <br>
    <a href="addemployee.php">Add Employee</a> <br>
    <a href="index.php">Index</a>

    <footer> © Rohit Kumar Sahoo</footer>
</body>

</html>