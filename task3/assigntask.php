<?php
include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task_name = trim($_POST['task_details']);
    $selected_employees_array = isset($_POST['selected_employees_div_data']) ? $_POST['selected_employees_div_data'] : [];

    if (empty($task_name) || empty($selected_employees_array)) {
        echo "<script>alert('Cannot be empty select employee then assign task');window.location.href = 'assigntask.php';</script>";
    }

    // Convert selected employees array to a comma-separated string
    $assign_to = implode(',', $selected_employees_array);

    $sql = "INSERT INTO taskassign (task_name, assign_to) VALUES ('$task_name', '$assign_to')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Task Assigned Successfully');window.location.href = 'assigntask.php'</script>";
    } else {
        // $error = $conn->error;
        echo "<script>alert('Cannot assign same task: '); window.location.href = 'assigntask.php'</script>";
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
        <form id="my_form" method="post">
            <label>Select employee:</label>
            <div id="dropdown_container_main">
                <div class="dropdown_wrapper_child">
                    <select class="dropdown_menu">
                        <option value="">-- Select Employee --</option>
                    </select>
                    <button type="button" class="remove_employee">Remove</button>
                </div>
            </div>
            <span id="employee_error" style="color: red;"></span>
            <br><br>

            <button class="addbtn" type="button" id="add_employee">ADD</button>
            <br><br>

            <span id="task_error" style="color: red;"></span>
            <br>

            <textarea name="task_details" id="task_details" required></textarea>
            <button class="addbtn" type="submit" name="add_task">Submit</button>
        </form>
    </div>

    <script src="js/jquery.js"></script>
    <script>
        $(document).ready(function () {
            console.log("ajax loaded");

            // Load employees on page load
            $.post("load-employee.php", function (data) {
                $(".dropdown_menu").append(data); // Append employees to all dropdowns
            });

            // Add new dropdown when clicking "ADD"
            $("#add_employee").click(function () {
                let clone = $(".dropdown_wrapper_child:first").clone(); // Clone first dropdown
                clone.find("select").val(""); // Reset dropdown selection
                $("#dropdown_container_main").append(clone); // Append cloned dropdown
            });

            // When selecting an employee, hide from other dropdowns
            $(document).on("change", ".dropdown_menu", function () {
                let selected_emp_id = $(this).val();
                console.log(" selected employee id = ", selected_emp_id);

                // Hide the selected employee from other dropdowns
                $(".dropdown_menu").not(this).find(`option[value="${selected_emp_id}"]`).hide();
            });

            // When clicking "Remove", add employee back to all dropdowns and remove the div
            $(document).on("click", ".remove_employee", function () {
                let dropdown_wrapper = $(this).closest(".dropdown_wrapper_child");
                let emp_id = dropdown_wrapper.find("select").val();

                // Show the removed employee back in all dropdowns
                $(".dropdown option[value='" + emp_id + "']").show();

                dropdown_wrapper.remove(); // Remove dropdown wrapper
            });

            // Form validation
            $("#my_form").submit(function (e) {
                let selected_count = $(".dropdown_menu").filter(function () {
                    return $(this).val() !== "";
                }).length;

                let task_details = $("#task_details").val().trim();
                let is_valid = true;

                if (selected_count === 0) {
                    $("#employee_error").text("Please select at least one employee.");
                    is_valid = false;
                } else {
                    $("#employee_error").text("");
                }

                if (task_details === "") {
                    $("#task_error").text("Please enter task details.");
                    is_valid = false;
                } else {
                    $("#task_error").text("");
                }

                if (!is_valid) {
                    e.preventDefault();
                }
            });
        });
    </script>



    </script>

    <!-- form validation using jquery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            // No leading spaces validation
            $.validator.addMethod("noleadingspace", function (value, element) {
                return this.optional(element) || /^\S.*$/.test(value); // first character is not a space
            }, "Leading spaces are not allowed");

            // regex validation method (Allows letters, numbers, spaces, apostrophes, and dots)
            $.validator.addMethod("regex", function (value, element) {
                return this.optional(element) || /^[a-zA-Z0-9'.\s]{1,40}$/.test(value);
            }, "Only letters, numbers, spaces, apostrophes, and dots are allowed (Max 40 characters)");

            // Apply validation to the form
            $("#my_form").validate({
                rules: {
                    task_details: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    task_details: {
                        required: "Task is required",
                        regex: "Enter valid details (letters, numbers, spaces, apostrophes, dots allowed)",
                        noleadingspace: "Leading spaces are not allowed"
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>

    <footer> © Rohit Kumar Sahoo</footer>



</body>

</html>