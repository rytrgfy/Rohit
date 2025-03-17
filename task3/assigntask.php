<?php
include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task_name = trim($_POST['taskdetails']);
    $selectedEmployees = isset($_POST['selectedEmployees']) ? $_POST['selectedEmployees'] : [];

    if (empty($task_name) || empty($selectedEmployees)) {
        echo "<script>alert('Cannot be empty select employee then assign task');window.location.href = 'assigntask.php';</script>";

    }

    // Convert selected employees array to a comma-separated string
    $assign_to = implode(',', $selectedEmployees);

    $sql = "INSERT INTO taskassign (task_name, assign_to) VALUES ('$task_name', '$assign_to')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Task Assigned Successfully');window.location.href = 'assigntask.php'</script>";
    } else {
        $error = $conn->error;
        echo "<script>alert('Cannot assign same task: " . addslashes($error) . "'); window.location.href = 'assigntask.php'</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>assigntask</title>
    <style>
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            margin-top: auto;
        }

        .error {
            color: red;
        }

        body {
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        form {
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 2px 2px 5px gray;
            max-width: 350px;
            margin: auto;
        }

        h2 {
            color: black;
            font-size: 18px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        select,
        textarea,
        button {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid gray;
            margin-top: 5px;
        }

        .addbtn {
            background-color: green;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .addbtn {
            background-color: green;
            color: white;
            font-size: 16px;

        }

        .addbtn:hover {
            background-color: orange;
        }

        button {
            background-color: grey;
            color: #f8f9fa;
        }

        button:hover {
            background-color: hsl(0, 88.70%, 65.30%);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid gray;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: black;
            color: white;
        }

        tr:nth-child(even) {
            background-color: lightgray;
        }

        .highlight {
            border: 2px solid red;
            background-color: #ffe6e6;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .employee {
            color: cadetblue;
            font-size: x-large;
        }
    </style>
</head>



<body>

    <div class="container">
        <a href="index.php">GO-BACK</a>
        <h2>Assign Task</h2>
        <form method="post" id="myform">
            <label>Select employee:</label>
            <select id="employeenames">
                <option value="">-- Select Employee --</option>
            </select>
            <span id="employeeError" style="color: red;"></span>
            <br><br>

            <button class="addbtn" type="button" id="addEmployee">ADD</button>
            <br><br>

            <!-- Display selected employees -->
            <div id="selectedEmployees"></div>
            <span id="taskError" style="color: red;"></span>
            <br>

            <textarea name="taskdetails" id="taskdetails" required></textarea>
            <button class="addbtn" type="submit" name="add_task">Submit</button>
            
            

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
                console.log(empName, empId);

                if (empId) {
                    $("#selectedEmployees").append(
                        `<div class="employee" data-id="${empId}">
                    ${empName} <button class="removeEmployee">Remove</button>
                    <input type="hidden" name="selectedEmployees[]" value="${empId}">
                </div>`
                    );
                    selectedOption.remove(); // Remove from dropdown

                    // Remove error message if employee is added
                    $("#employeeError").text("");
                } else {
                    $("#employeeError").text("Please select an employee.");
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

            // Form submission validation
            $("form").submit(function (e) {
                let selectedEmployees = $("#selectedEmployees").children().length;
                let taskDetails = $("#taskdetails").val().trim();
                let isValid = true;

                // Validate employee selection
                if (selectedEmployees === 0) {
                    $("#employeeError").text("Please select at least one employee.");
                    isValid = false;
                } else {
                    $("#employeeError").text("");
                }

                // Validate task details
                if (taskDetails === "") {
                    $("#taskError").text("Task details cannot be empty.");
                    isValid = false;
                } else {
                    $("#taskError").text("");
                }

                if (!isValid) {
                    e.preventDefault(); // Prevent form submission if validation fails
                }
            });
        });

    </script>



    <!-- form validation using j query  -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {

            // No leading spaces validation
            $.validator.addMethod("noleadingspace", function (value, element) {
                return this.optional(element) || /^\S.*$/.test(value); //  first character is not a space
            }, "Leading spaces are not allowed");

            // regex validation method (Allows letters, numbers, spaces, apostrophes, and dots)
            $.validator.addMethod("regex", function (value, element) {
                return this.optional(element) || /^[a-zA-Z0-9'.\s]{1,40}$/.test(value);
            }, "Only letters, numbers, spaces, apostrophes, and dots are allowed (Max 40 characters)");

            // Apply validation to the form
            $("#myform").validate({
                rules: {
                    taskdetails: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    taskdetails: {
                        required: "Task is required",
                        regex: "Enter valid details (letters, numbers, spaces, apostrophes, dots allowed)",
                        noleadingspace: "Leading spaces are not allowed"
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function (form) {
                    // alert("Form submitted successfully!");
                    form.submit();
                }
            });

        });
    </script>


    <footer> © Rohit Kumar Sahoo</footer>
</body>

</html>