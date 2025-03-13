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
        background-color: #f4f4f4;
        padding: 20px;
    }

    form {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: auto;
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
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    button {
        background-color: #28a745;
        color: white;
        font-size: 16px;
        cursor: pointer;
        margin-top: 15px;
    }

    button:hover {
        background-color: rgb(241, 159, 5);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>
</head>


<h2 style="text-align: center;">Add Details</h2>
<form method="POST" id="myform">
    Select State:
    <select id="stateSelect" name="state">
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

        $sql_details_value = "
            SELECT sd.details, s.state_name, sd.district_name
            FROM state_details sd
            JOIN states s ON s.id = sd.state_id order by s.state_name";
        // Use correct table name
        
        $result = $conn->query($sql_details_value);

        if (!$result) {
            die("Query Error: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            $num = 1;
            while ($row = $result->fetch_assoc()) {
                // Check if all values are not empty then only show
                if (!empty(trim($row['state_name'])) && !empty(trim($row['district_name'])) && !empty(trim($row['details']))) {
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
        console.log('enter working');
        function loadData(type, category_id) {
            console.log('enter inside function working');
            $.ajax({
                url: "load-cs.php",
                type: "POST",
                data: { type: type, id: category_id },
                success: function (data) {
                    if (type == "district") {
                        $("#districtSelect").html(data);
                        console.log('enter inside district working');
                    } else {
                        $("#stateSelect").append(data);
                        console.log('enter inside else state  working');
                    }
                }
            });
        }

        // this will load initially
        loadData("state");

        // Change event for state selection
        $("#stateSelect").on("change", function () {
            console.log('enter state selection and distict working');
            var state = $(this).val(); // to get state value when change

            if (state != "") {
                loadData("district", state);
            } else {
                $("#districtSelect").html('<option value="">Select district</option>');
            }
        });
        // to get distict id
        $("#districtSelect").on("change", function () {
            var districtId = $(this).val(); // Get selected district id
            console.log("Selected District ID: " + districtId + "  working");


            if (districtId != "") {
                $.post("senddetails.php", { district_id: districtId }, function (data) {
                    $("#details").val(data);  // Set details in textarea
                    console.log("for details working");
                });
            } else {
                $("#details").val("");
                console.console.log('this is else part working');
                // Clear if no district is selected
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