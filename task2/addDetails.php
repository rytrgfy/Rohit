<?php
include "dbconn.php";
$sql_get_data = "SELECT * FROM states ORDER BY state_name ASC";
$result = $conn->query($sql_get_data);

if (!$result) {
    die("No data found");
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
            font-family: Arial, sans-serif;
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
    <form action="/submit-details" method="post">
        Select State:
        <select id="stateSelect" name="state">
            <option value="">Select State</option>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['state_name']}</option>";
            }
            ?>
        </select>




        <label for="districtSelect">Select District:</label>
        <select id="districtSelect" name="district" required>
            <option value="">--Select District--</option>
        </select>





        <label for="details">Details:</label>
        <textarea id="details" name="details" rows="4" required></textarea>

        <button type="submit">Submit</button>

    </form>
</body>

</html>