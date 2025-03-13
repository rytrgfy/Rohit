<?php
include "dbconn.php";

$details = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $district_id = isset($_POST['district']) ? (int) $_POST['district'] : 0; // Validate ID
    $details = $_POST['details'];

    if ($district_id > 0) {
        $sql = "UPDATE state_details SET details = '$details' WHERE id = $district_id";

        if (!$conn->query($sql)) {
            echo "Error updating record: " . $conn->error;
        } else {
            echo "<script>window.location.href = 'addDetails.php'</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Details</title>

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
    <h2 style="text-align: center;">Add Details</h2>
    <form method="POST" id="myform">
        Select State:
        <select id="stateSelect" name="state" focus>
            <option value="">Select State</option>
        </select>

        Select District:
        <select id="districtSelect" name="district">
            <option value="">Select District</option>
        </select>

        Details:
        <textarea id="details" name="details"></textarea>

        <button type="submit">Submit</button>
    </form>




    go back:➡️<a href="index.php">click me </a>

    <table>
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>State</th>
                <th>District</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $sql_details_value = "SELECT sd.details, s.state_name, sd.district_name
            FROM state_details sd
            JOIN states s ON s.id = sd.state_id 
            WHERE sd.details IS NOT NULL AND sd.details <> '' 
            ORDER BY s.state_name ASC;"
            ;






            // Use correct table name
            
            $result = $conn->query($sql_details_value);

            if (!$result) {
                die("Query Error: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                $num = 1;
                while ($row = $result->fetch_assoc()) {
                    // Check if all values are not empty then only show
                    if (!empty($row['state_name']) && !empty($row['district_name']) && !empty($row['details'])) {
                        ?>
                        <tr>
                            <td><?php echo $num++; ?></td>
                            <td><?php echo $row['state_name']; ?></td>
                            <td><?php echo $row['district_name']; ?></td>
                            <td><?php echo $row['details']; ?></td>
                        </tr>
                        <?php
                    }
                }
            } else { ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No details available.</td>
                </tr>
            <?php } ?>

        </tbody>
    </table>




    <!-- this is    ajax  -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            console.log('Script loaded');

            function loadData(type, category_id) {
                $.ajax({
                    url: "load-cs.php",
                    type: "POST",
                    data: { type: type, id: category_id },
                    success: function (data) {
                        if (type == "district") {
                            $("#districtSelect").html(data);
                        } else {
                            $("#stateSelect").append(data);
                        }
                    }
                });
            }

            // Load states initially
            loadData("state");

            // Disable district and details initially
            $("#districtSelect").prop("disabled", true);
            $("#details").prop("disabled", true);
            $("button[type='submit']").prop("disabled", true);

            // When state changes, load districts and enable district dropdown
            $("#stateSelect").on("change", function () {
                var state = $(this).val();
                if (state != "") {
                    loadData("district", state);
                    $("#districtSelect").prop("disabled", false);
                } else {
                    $("#districtSelect").html('<option value="">Select district</option>').prop("disabled", true);
                    $("#details").prop("disabled", true);
                    $("button[type='submit']").prop("disabled", true);
                }
            });

            // When district is selected, check if details exist
            $("#districtSelect").on("change", function () {
                var districtId = $(this).val();
                console.log("Selected District ID: " + districtId);

                if (districtId !== "") {
                    $.post("senddetails.php", { district_id: districtId }, function (data) {
                        console.log("Response from server: " + data.trim());

                        if (data.trim() === "exists") {
                            alert("You cannot modify the details. They already exist.");
                            $("#details").prop("disabled", true);
                            $("button[type='submit']").prop("disabled", true);
                        } else {
                            $("#details").prop("disabled", false);
                            $("button[type='submit']").prop("disabled", false);
                            $("#details").focus(); // Move focus to details field
                        }
                    });
                } else {
                    $("#details").prop("disabled", true);
                    $("button[type='submit']").prop("disabled", true);
                }
            });
        });




    </script>


    <!-- ajax end's here -->


    <!-- validations starts here  -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {

            // No leading spaces validation
            $.validator.addMethod("noleadingspace", function (value, element) {
                return this.optional(element) || /^\S.*$/.test(value); // Ensures the first character is not a space
            }, "Leading spaces are not allowed");

            // Custom regex validation method (Allows letters, numbers, spaces, apostrophes, and dots)
            $.validator.addMethod("regex", function (value, element) {
                return this.optional(element) || /^[a-zA-Z0-9'.\s]{1,40}$/.test(value);
            }, "Only letters, numbers, spaces, apostrophes, and dots are allowed (Max 40 characters)");

            // Apply validation to the form
            $("#myform").validate({
                rules: {
                    details: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    details: {
                        required: "Details are required",
                        regex: "Enter valid details (letters, numbers, spaces, apostrophes, dots allowed)",
                        noleadingspace: "Leading spaces are not allowed"
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