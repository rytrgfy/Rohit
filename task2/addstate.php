<?php
include "dbconn.php";

$sql_get_state = "SELECT * FROM states ORDER BY id ASC";
$res = $conn->query($sql_get_state);
if (!$res) {
    echo 'Error: ' . $conn->error;
}

$state = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $state = strtolower(trim($_POST['state'])); // Trim spaces to prevent empty inputs

    if (!empty($state)) {
        // Check if state already exists
        $check_sql = "SELECT * FROM states WHERE LOWER(state_name) = '$state'";
        $check_res = $conn->query($check_sql);

        if ($check_res->num_rows > 0) {
            echo "<script>alert('State already exists!'); window.location.href='addstate.php';</script>";
        } else {
            // Insert new state
            $sql = "INSERT INTO states (state_name) VALUES ('$state')";
            if (!$conn->query($sql)) {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            } else {
                echo "<script>alert('State added successfully'); window.location.href='addstate.php';</script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add State</title>
    <style>
        body {
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
            font-family: Arial, sans-serif;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 350px;
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            display: block;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }

        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
            background: white;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #ddd;
        }

        .no-data {
            color: red;
        }
    </style>
</head>

<body>

    <p>Go back:➡️ <a href="index.php">Click me</a></p>
    
    <div class="container">
        <h2>Add State</h2>
        <form action="" method="post" id="myform">
            <label for="state">State Name:</label>
            <input type="text" id="state" name="state">
            <span id="stateError" class="error"></span>
            <button type="submit">Submit</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>State</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($res && $res->num_rows > 0) {
                $num = 1;
                while ($i = $res->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $num++; ?></td>
                        <td><?php echo !empty(trim($i['state_name'])) ? $i['state_name'] : '<span class="no-data">N/A</span>'; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="2" class="no-data">No data available.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- jQuery and jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            // Custom validation methods
            $.validator.addMethod("noleadingspace", function (value, element) {
                return this.optional(element) || /^\S.*$/.test(value);
            }, "Leading spaces are not allowed");

            $.validator.addMethod("regex", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Only letters and spaces are allowed");

            // Apply validation
            $("#myform").validate({
                rules: {
                    state: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    state: {
                        required: "State is required",
                        regex: "Enter valid details (letters only)",
                        noleadingspace: "Leading spaces are not allowed"
                    }
                },
                errorPlacement: function (error, element) {
                    $("#stateError").html(error);
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>

</body>
</html>
