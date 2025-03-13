<?php
include "dbconn.php";

// Fetch states for dropdown and listing
$sql_get_states = "SELECT * FROM states ORDER BY id ASC";
$res_states = $conn->query($sql_get_states);

// Fetch states district details with proper joins
$sql_get_districts = "SELECT s.id as state_id, s.state_name, d.id as district_id, d.district_name 
                      FROM states s 
                      LEFT JOIN state_details d ON s.id = d.state_id 
                      ORDER BY s.id ASC, d.district_name ASC";
$res_districts = $conn->query($sql_get_districts);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_district'])) {
        // Add District
        $state_id = $_POST['state'];
        $district = strtolower($_POST['district']);

        if (!empty($state_id) && !empty($district)) {
            $sql_insert_district = "INSERT INTO state_details (state_id, district_name) VALUES ('$state_id', '$district')";

            if ($conn->query($sql_insert_district)) {
                echo "<script>alert('District added successfully'); window.location.href='';</script>";
            } else {
                echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
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
    <title>Add distict</title>
    <style>
        body {
            background-color: rgb(255, 255, 255);
            text-align: center;
            padding: 50px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(148, 45, 45, 0.1);
            display: inline-block;
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
        }

        input,
        select {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 80%;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: rgb(161, 167, 173);
            color: white;
        }
    </style>
</head>

<body>


    <div class="container" style="margin-top: 20px;">
        <h2>Add District</h2>
        <form method="post" id="myform">
            Select State:
            <select name="state">
                <option value="">--Select State--</option>
                <?php while ($row = $res_states->fetch_assoc()) { ?>
                    <option value="<?= $row['id'] ?>"><?= $row['state_name'] ?></option>
                <?php } ?>
            </select>

            <label for="district">District Name:</label>
            <input type="text" id="district" name="district" required>
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
                    <td> <?php echo $num ?></td>
                    <td> <?php echo $i['state_name'] ?></td>
                    <td> <?php echo !empty($i['district_name']) ? $i['district_name'] : 'N/A' ?> </td>
                </tr>
                <?php $num++; ?>
            <?php } ?>

        </tbody>
    </table>




    <!--  validation  -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {

            // No leading spaces validation
            $.validator.addMethod("noleadingspace", function (value, element) {
                return this.optional(element) || /^\S.*$/.test(value);
            }, "Leading spaces are not allowed");

            $.validator.addMethod("regex", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Only letters and spaces are allowed");


            // Apply validation to the form
            $("#myform").validate({
                rules: {
                    district: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    district: {
                        required: "district are required",
                        regex: "Enter valid details (letters)",
                        noleadingspace: "spaces not allowed"
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function (form) {
                    alert("Form submitted successfully!");
                    form.submit();
                }
            });

        });
    </script>
</body>

</html>