<?php
include "dbconn.php";
session_start();

// Updated SQL JOIN query to include cities

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != '78') {
    header("Location: 404.php");
    exit();
}

// Fetch states for dropdown and listing
$sql_get_states = "SELECT * FROM state ORDER BY id ASC";
$res_states = $conn->query($sql_get_states);

// Fetch states district details with proper joins
$sql_get_districts = "SELECT DISTINCT d.id AS district_id, d.district_name, s.state_name 
                      FROM district d
                      JOIN state s ON s.id = d.state_id
                      ORDER BY s.state_name, d.district_name";

$res_districts = $conn->query($sql_get_districts);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_district'])) {
        // Add District
        $state_id = $_POST['state'];
        $district = strtolower($_POST['district']);

        if (!empty($state_id) && !empty($district)) {
            $sql_insert_district = "INSERT INTO district (state_id, district_name) VALUES ('$state_id', '$district')";

            if ($conn->query($sql_insert_district)) {
                echo "<script>alert('District added successfully'); window.location.href='addDistict.php';</script>";
            } else {
                // echo "<p style='color: red;'>Error: 'duplicate entry not allowed'</p>";
                echo $conn->error;
            }
        } else {
            echo "<p style='color: red;'>Please select a state and enter a district name.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add District</title>
    <style>
        body {
            background-color: rgb(161, 162, 163);
            text-align: center;
            padding: 50px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            display: inline-block;
            width: 400px;
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input,
        select {
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 90%;
            font-size: 16px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }

        button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }

        a {
            text-decoration: none;
            color: rgb(255, 255, 255);
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <p>Go back: ➡️ <a href="admin.php">Click me</a></p>

    <div class="container">
        <h2>Add District</h2>
        <form method="post" id="myform">
            <label>Select State:</label>
            <select name="state">
                <option value="">-- Select State --</option>
                <?php while ($row = $res_states->fetch_assoc()) { ?>
                    <option value="<?= $row['id'] ?>"><?= $row['state_name'] ?></option>
                <?php } ?>
            </select>

            <label for="district">District Name:</label>
            <input type="text" id="district" name="district">
            <button type="submit" name="add_district">Submit</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>State</th>
                <th>District</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $num = 1;
            while ($i = $res_districts->fetch_assoc()) { ?>
                <tr>
                    <td> <?php echo $num++ ?></td>
                    <td> <?php echo !empty($i['state_name']) ? $i['state_name'] : '<span style="color:red;">N/A</span>' ?>
                    </td>
                    <td> <?php echo !empty($i['district_name']) ? $i['district_name'] : '<span style="color:red;">N/A</span>' ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            $.validator.addMethod("noleadingspace", function (value, element) {
                return this.optional(element) || /^\S.*$/.test(value);
            }, "Leading spaces are not allowed");

            $.validator.addMethod("regex", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Only letters and spaces are allowed");

            $("#myform").validate({
                rules: {
                    state: {
                        required: true
                    },
                    district: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    state: {
                        required: "Please select a state."
                    },
                    district: {
                        required: "District name is required.",
                        regex: "Only letters and spaces are allowed.",
                        noleadingspace: "Spaces are not allowed at the beginning."
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });
        });
    </script>
</body>

</html>