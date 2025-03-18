<?php


include "dbconn.php";
$sql = "SELECT * FROM taskassign";
$res = $conn->query($sql);

// Process status update if form is submitted
if (isset($_POST['update_status']) && isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = intval($_POST['task_id']);
    $status = intval($_POST['status']);

    $update_sql = "UPDATE taskassign SET status = $status WHERE id = $task_id";
    if ($conn->query($update_sql)) {
        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Optional: Add some styling for the dropdown */
        select.status_dropdown {
            padding: 1px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .status_form {
            margin: 0;
        }

        .status_todo {
            background-color: lightgray;
        }

        .status_inprogress {
            background-color: rgb(226, 202, 124);
        }

        .status_completed {
            background-color: rgb(148, 231, 192);
        }
    </style>
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
                <th>Action</th>
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

                        // Get the current status (default to 0 if not set)
                        $status = isset($row['status']) ? intval($row['status']) : 0;

                        // set status class based on 0,1,2
                        $status_class_for_css = "";
                        switch ($status) {
                            case 1:
                                $status_class_for_css = "status_completed";
                                break;
                            case 2:
                                $status_class_for_css = "status_inprogress";
                                break;
                            default:
                                $status_class_for_css = "status_todo";
                        }

                        echo "<tr class='$status_class_for_css'>
                            <td>{$num}</td>
                            <td>{$row['task_name']}</td>
                            <td>$employee_names_str</td>
                            <td>
                                <form class='status_form' method='post'>
                                    <input type='hidden' name='task_id' value='{$row['id']}'>
                                    <select name='status' class='status_dropdown' onchange='this.form.submit()'>
                                        <option value='0'" . ($status === 0 ? ' selected' : '') . ">Todo</option>
                                        <option value='2'" . ($status === 2 ? ' selected' : '') . ">In Progress</option>
                                        <option value='1'" . ($status === 1 ? ' selected' : '') . ">Completed</option>
                                    </select>
                                    <input type='hidden' name='update_status' value='1'>
                                </form>
                            </td>
                        </tr>";

                        $num++;
                    }
                }
            } else { ?>
                <tr>
                    <td colspan="4" class="no-data">No tasks assigned.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br><br>
    <footer> © Rohit Kumar Sahoo</footer>
</body>

</html>