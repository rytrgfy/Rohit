<?php
include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task_detail = $_POST['task_details'];
    $emp_selection = isset($_POST['selected_employees']) ? (array) $_POST['selected_employees'] : [];

    if (empty($task_detail) || empty($emp_selection)) {
        // echo "cannot go" . "<br>";
        echo "<script>alert('Cannot be empty select employee then assign task');window.location.href = 'assigntask.php';</script>";
    }
    

    // // Convert selected employees array to a comma-separated string
    $assigned_people = implode(',', $emp_selection);

    // echo "<pre>";
    // print_r($selected_employees_array);
    // echo "</pre>";




    $query_str = "INSERT INTO taskassign (task_name, assign_to, status) VALUES ('$task_detail', '$assigned_people', 0)";

    if ($conn->query($query_str) === TRUE) {
        echo "<script>alert('Task Assigned Successfully');window.location.href = 'assigntask.php'</script>";
        // exit();
    } else {
        // $error = $conn->error;
        echo "<script>alert('Cannot assign same task: '); window.location.href = 'assigntask.php'</script>";
    }
    // echo $assign_to;
    // echo $task_name;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>assigntask</title>
    
    <link rel="stylesheet" href="style.css">
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
                    <button type="button" class="remove_employee">Remove (-)</button>
                </div>
            </div>
            <input type="hidden" name="selected_employees" id="selected_employees_input">

            <span id="employee_error" style="color: red;"></span>
            <br><br>

            <button class="addbtn" type="button" id="add_employee">ADD(+)</button>
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
            $.post("load-employee.php", function (response_data) {
                $(".dropdown_menu").append(response_data);
            });

            // Function to update dropdown options dynamically
            function refresh_employee_list() {
                let emp_list = [];

                // Collect all selected employees
                $(".dropdown_menu").each(function () {
                    let emp_id = $(this).val();
                    if (emp_id) {
                        emp_list.push(emp_id);
                    }
                });

                // Reset visibility of all options first
                $(".dropdown_menu option").show();

                // Hide selected employees from all dropdowns
                $(".dropdown_menu").each(function () {
                    let menu_elem = $(this);
                    emp_list.forEach(function (emp_id) {
                        menu_elem.find(`option[value="${emp_id}"]`).hide();
                    });
                });
            }

            // Add new dropdown when clicking "Add Employee"
            $("#add_employee").click(function () {
                let dup_el = $(".dropdown_wrapper_child:first").clone(); // Clone the first dropdown
                dup_el.find("select").val(""); // Reset dropdown selection
                $("#dropdown_container_main").append(dup_el); // Append cloned dropdown

                refresh_employee_list(); // Update dropdown options to remove already selected employees
            });

            // Prevent duplicate selection across dropdowns
            $(document).on("change", ".dropdown_menu", function () {
                refresh_employee_list();
            });

            // When clicking "Remove", restore employee option and remove the div
            $(document).on("click", ".remove_employee", function () {
                let dropdown_row = $(this).closest(".dropdown_wrapper_child");
                dropdown_row.remove(); // Remove dropdown

                refresh_employee_list(); // Refresh dropdown options after removal

                // Ensure at least one dropdown remains
                if ($(".dropdown_wrapper_child").length === 0) {
                    alert("At least one employee selection is required!");
                    window.location.href = "assigntask.php";
                    $("#add_employee").trigger("click"); // Auto-add one dropdown if all are removed
                }
            });

            // Form validation before submission
            $("#my_form").submit(function (ev) {
                let employee_ids = [];

                // Collect all selected employee IDs
                $(".dropdown_menu").each(function () {
                    let emp_id = $(this).val();
                    if (emp_id) {
                        employee_ids.push(emp_id);
                    }
                });

                console.log("Final Selected Employees Array:", employee_ids);

                // Store selected employees in a hidden input
                $("#selected_employees_input").val(employee_ids.join(","));

                let work_detail = $("#task_details").val().trim();
                let form_ok = true;

                if (employee_ids.length === 0) {
                    $("#employee_error").text("Please select at least one employee.");
                    form_ok = false;
                } else {
                    $("#employee_error").text("");
                }

                if (work_detail === "") {
                    $("#task_error").text("");
                    form_ok = false;
                } else {
                    $("#task_error").text("");
                }

                if (!form_ok) {
                    ev.preventDefault();
                }
            });
        });


    </script>


    <!-- form validation using jquery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            // No leading spaces validation
            $.validator.addMethod("noleadingspace", function (val, e) {
                return this.optional(e) || /^\S.*$/.test(val); // first character is not a space
            }, "Leading spaces are not allowed");

            // regex validation method (Allows letters, numbers, spaces, apostrophes, and dots)
            $.validator.addMethod("regex", function (val, e) {
                return this.optional(e) || /^[a-zA-Z0-9'.\s]{1,40}$/.test(val);
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
                errorPlacement: function (err_msg, e) {
                    err_msg.insertAfter(e);
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