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
 <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <h1>Task Alloted to employees</h1>
        <div class="nav-links">

            <a href="assigntask.php">Assign Task</a>
            <a href="addemployee.php">Add Employee</a>
            <a href="index.php">Index</a>
        </div>
    </div>
    <br>

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
                        $count = 1;
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
                                    // $employee_names[] = $names['employeename'];
                                    $employee_names[] = $count . '.' . $names['employeename'];
                                    // echo $names['employeename'] . "<br><br><br>" ;
                                    $count++;
                                }
                                // print_r()
                                // echo count($employee_names ) . "<br>";
                            }
                        }
                        $employee_names_str = !empty($employee_names) ? implode("<br>", $employee_names) : '<span style="color:red;">No Employees Assigned</span>';
                        // var_dump($employee_names_str) . "<br>";
                        echo "<tr>
                            <td>{$num}</td>
                            <td>{$row['task_name']}</td>
                            <td>$employee_names_str</td>
                            </tr>";

                        // if ($employee_names) {
                        //     foreach ($employee_names as $name) {
                                // echo "<li>{$name}</li>";
                        //     }
                        // } else {
                            // echo "//<li style='color:red;'>No Employees Assigned</li>";
                        // }

                        


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


    <footer> © Rohit Kumar Sahoo</footer>
</body>

</html>