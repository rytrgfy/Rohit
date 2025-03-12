<?php
include "dbconn.php";

$details = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $district_id = $_POST['district']; // Get the selected district ID


    $details = $_POST['details'];
    $sql = "UPDATE state_details SET details = '$details' WHERE id = $district_id;";

    if (!$conn->query($sql)) {
        echo "Error updating record: " . $conn->error;
    }

    $sql_details_value = "SELECT details FROM state_details WHERE id = $district_id";
    $result = $conn->query($sql_details_value);
    $row = $result->fetch_assoc();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Details</title>
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
            background-color: #218838;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Add Details</h2>
    <form method="POST">


        Select State:
        <select id="stateSelect" name="state">
            <option value="">Select State</option>
        </select>




        Select District:
        <select id="districtSelect" name="district" required>
            <option value="">Select District</option>
        </select>

        Details:
        <textarea id="details" name="details" rows="4"></textarea>

        <button type="submit">Submit</button>
    </form>

    </form>






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
                            $("#districtSelect").html(data);
                        } else {
                            $("#stateSelect").append(data);
                        }
                    }
                });
            }

            // this will load initially
            loadData("state");

            // Change event for state selection
            $("#stateSelect").on("change", function () {
                var state = $(this).val(); // to get state value when change

                if (state != "") {
                    loadData("district", state);
                } else {
                    $("#districtSelect").html('<option value="">Select District</option>');
                }
            });
            // to get distict id
            $("#districtSelect").on("change", function () {
                var districtId = $(this).val(); // Get selected district id
                console.log("Selected District ID: " + districtId);


                if (districtId != "") {
                    $.post("senddetails.php", { district_id: districtId }, function (data) {
                        $("#details").val(data);  // Set details in textarea
                    });
                } else {
                    $("#details").val(""); // Clear if no district is selected
                }
            });

        });
    </script>
</body>

</html>