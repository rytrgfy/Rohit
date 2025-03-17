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
    <style>
        /* Reset default browser styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }

        /* Form container */
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 380px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Label styling */
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
        }

        /* Input fields */
        input {
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 16px;
        }

        /* Error message styling */
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Submit button styling */
        button {
            background-color: #28a745;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #218838;
        }

        /* Navigation links */
        .nav-links {
            margin-top: 15px;
        }

        .nav-links a {
            display: block;
            color: blue;
            text-decoration: none;
            font-size: 16px;
            margin: 5px 0;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
        }
    </style>
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
            <a href="index.php">Index</a>
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