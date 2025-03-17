<?php
include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task_name = trim($_POST['taskdetails']);
    $selectedEmployees = isset($_POST['selectedEmployees']) ? $_POST['selectedEmployees'] : [];

    if (empty($task_name) || empty($selectedEmployees)) {
        echo "Task name could'nt be empty and one employee atleast must be selected.";
        exit;
    }

    // Convert selected employees array to a comma-separated string
    $assign_to = implode(',', $selectedEmployees);

    $sql = "INSERT INTO taskassign (task_name, assign_to) VALUES ('$task_name', '$assign_to')";

    if ($conn->query($sql) === TRUE) {
        echo "Task assigned successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>assigntask</title>
</head>



<body>

    <div class="container">
        <h2>Assign Task</h2>
        <form method="post" id="myform">
            <label>Select employee:</label>
            <select id="employeenames">
                <option value="">-- Select Employee --</option>
            </select>
            <br><br>

            <button type="button" id="addEmployee">ADD</button>
            <br><br>

            <!-- Display selected employees -->
            <div id="selectedEmployees"></div>
            <br>

            <textarea name="taskdetails" id="taskdetails"></textarea>
            <button type="submit" name="add_task">Submit</button>
        </form>
    </div>

    <script src="js/jquery.js"></script>
    <script>
        $(document).ready(function () {
            console.log("ajax loaded");
            // Load employees on page load
            $.post("load-employee.php", function (data) {
                $("#employeenames").append(data);
            });

            // Add employee to list & remove from dropdown
            $("#addEmployee").click(function () {
                let selectedOption = $("#employeenames option:selected");
                let empId = selectedOption.val();
                let empName = selectedOption.text();
                console.log(empName , empId);

                if (empId) {
                    $("#selectedEmployees").append(
                        `<div class="employee" data-id="${empId}">
                        ${empName} <button class="removeEmployee">Remove</button>
                        <input type="hidden" name="selectedEmployees[]" value="${empId}">
                    </div>`
                    );
                    selectedOption.remove(); // Remove from dropdown
                }
            });

            // Remove employee from list & add back to dropdown
            $(document).on("click", ".removeEmployee", function () {
                let employeeDiv = $(this).closest(".employee");
                let empId = employeeDiv.data("id");
                let empName = employeeDiv.text().replace("Remove", "").trim();

                $("#employeenames").append(`<option value="${empId}">${empName}</option>`);
                employeeDiv.remove(); // Remove from selected list
            });
        });
    </script>

</body>



</html>