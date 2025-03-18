<?php
include "dbconn.php";

$addemployee = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $addemployee = $_POST['addemployee'];


    $sql = "INSERT INTO emp_name (employeename) VALUES ('$addemployee')";
    if (!$conn->query($sql)) {
        echo "<script>alert('Error: Name already exists');window.location.href='addemployee.php';</script>";
    } else {
        echo "<script>alert('employee added successfull');window.location.href='addemployee.php';</script>";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add-Employee</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Add Employee</h2>
        <form action="" method="POST" id="myform">
            <label for="addemployee">Employee Name:</label>
            <input type="text" id="addemployee" name="addemployee" placeholder="Enter employee name" required>
            <span class="error"></span>
            <button type="submit">Submit</button>
        </form>

        <div class="nav-links">
            <a href="assigntask.php">Assign Task</a>
            <a href="addemployee.php">Add Employee</a>
            <a href="index.php">View all Data</a>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {

            // No leading spaces validation
            $.validator.addMethod("noleadingspace", function (value, element) {
                return this.optional(element) || /^\S.*$/.test(value); // Ensures the first character is not a space
            }, "Leading spaces are not allowed");

            $.validator.addMethod("regex", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\s]{1,40}$/.test(value);
            }, "Only letters and spaces are allowed (Max 40 characters)");


            // Apply validation to the form
            $("#myform").validate({
                rules: {
                    addemployee: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    addemployee: {
                        required: " This field is required",
                        regex: "Enter only letter",
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