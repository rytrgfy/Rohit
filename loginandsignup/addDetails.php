<?php
include "dbconn.php";
session_start();

// Updated SQL JOIN query to include cities

if (!isset($_SESSION['user_id'])) {
    header("Location:  404.php");
    exit();
}

$city = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $district_id = isset($_POST['district']) ? (int) $_POST['district'] : 0;
    $city = trim($_POST['city']);  // Trim spaces

    if ($district_id > 0 && !empty($city)) {
        $sql = "INSERT INTO city (district_id, city_name) VALUES ($district_id, '$city')";

        if (!$conn->query($sql)) {
            echo "Error inserting city: " . $conn->error;
        } else {
            echo "<script>window.location.href = 'adddetails.php'</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add City</title>

</html>
<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 20px;
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

    button {
        background-color: green;
        color: white;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
    }

    button:hover {
        background-color: orange;
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
</style>
</head>

<body>
    <h2 style="text-align: center;">Add City</h2>
    <form method="POST" id="myform">
        Select State:
        <select id="stateSelect" name="state" focus>
            <option value="">Select State</option>
        </select>

        Select District:
        <select id="districtSelect" name="district">
            <option value="">Select District</option>
        </select>

        City:
        <textarea id="city" name="city"></textarea>

        <button type="submit">Submit</button>
    </form>

    <a href="admin.php">Go Back</a>

    <table>
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>State</th>
                <th>District</th>
                <th>City</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT c.city_name, s.state_name, d.district_name
                FROM city c
                JOIN district d ON d.id = c.district_id 
                JOIN state s ON s.id = d.state_id 
                WHERE c.city_name IS NOT NULL 
                ORDER BY s.state_name ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $num = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$num}</td>
                        <td>{$row['state_name']}</td>
                        <td>{$row['district_name']}</td>
                        <td>{$row['city_name']}</td>
                      </tr>";
                    $num++;
                }
            } else {
                echo "<tr><td colspan='4' style='text-align: center;'>No city available.</td></tr>";
            }
            ?>
        </tbody>
    </table>


    <!-- Ajax for Dynamic Selection -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            function loadData(type, category_id) {
                $.ajax({
                    url: "load-cs.php",
                    type: "POST",
                    data: { type: type, id: category_id },
                    success: function (data) {
                        if (type == "district") {
                            $("#districtSelect").html(data).prop("disabled", false);
                            $("#citySelect").html('<option value="">Select City</option>').prop("disabled", true);
                        }
                        else if (type == "city") {
                            $("#citySelect").html(data).prop("disabled", false);
                        }
                        else {
                            $("#stateSelect").append(data);
                        }
                    }
                });
            }

            // Load states on page load
            loadData("state");

            // Disable dropdowns initially
            $("#districtSelect, #citySelect").prop("disabled", true);

            // Load districts on state change
            $("#stateSelect").on("change", function () {
                var state = $(this).val();
                if (state != "") {
                    loadData("district", state);
                } else {
                    $("#districtSelect, #citySelect").html('<option value="">Select</option>').prop("disabled", true);
                }
            });

            // Load cities on district change
            $("#districtSelect").on("change", function () {
                var district = $(this).val();
                if (district != "") {
                    loadData("city", district);
                } else {
                    $("#citySelect").html('<option value="">Select City</option>').prop("disabled", true);
                }
            });
        });

    </script>

    <!-- Form Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            $.validator.addMethod("noleadingspace", function (value, element) {
                return this.optional(element) || /^\S.*$/.test(value);
            }, "Leading spaces are not allowed");

            $.validator.addMethod("regex", function (value, element) {
                return this.optional(element) || /^[a-zA-Z0-9'.\s]{1,40}$/.test(value);
            }, "Only letters, numbers, spaces, apostrophes, and dots are allowed (Max 40 characters)");

            $("#myform").validate({
                rules: {
                    city: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    city: {
                        required: "City is required",
                        regex: "Enter a valid city name",
                        noleadingspace: "Leading spaces are not allowed"
                    }
                }
            });
        });
    </script>

</body>

</html>