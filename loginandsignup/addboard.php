<?php
include "dbconn.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != '78') {
    header("Location: 404.php");
    exit();
}

$sql_get_boards = "SELECT * FROM boards ORDER BY id ASC";
$res = $conn->query($sql_get_boards);
if (!$res) {
    echo 'Error: ' . $conn->error;
}

$board = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $board = strtolower(trim($_POST['board']));

    if (!empty($board)) {
        $check_sql = "SELECT * FROM boards WHERE LOWER(board_name) = '$board'";
        $check_res = $conn->query($check_sql);

        if ($check_res->num_rows > 0) {
            echo "<script>alert('Board already exists!'); window.location.href='addboard.php';</script>";
        } else {
            $sql = "INSERT INTO boards (board_name) VALUES ('$board')";
            if (!$conn->query($sql)) {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            } else {
                echo "<script>alert('Board added successfully'); window.location.href='addboard.php';</script>";
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
    <title>Add Board</title>
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
    <p>Go back:➡️ <a href="admin.php">Click me</a></p>
    
    <div class="container">
        <h2>Add Board</h2>
        <form action="" method="post" id="myform">
            <label for="board">Board Name:</label>
            <input type="text" id="board" name="board">
            <span id="boardError" class="error"></span>
            <button type="submit">Submit</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL.NO</th>
                <th>Board</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($res && $res->num_rows > 0) {
                $num = 1;
                while ($i = $res->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $num++; ?></td>
                        <td><?php echo !empty(trim($i['board_name'])) ? $i['board_name'] : '<span class="no-data">N/A</span>'; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="2" class="no-data">No data available.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

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
                    board: {
                        required: true,
                        regex: true,
                        noleadingspace: true
                    }
                },
                messages: {
                    board: {
                        required: "Board is required",
                        regex: "Enter valid details (letters only)",
                        noleadingspace: "Leading spaces are not allowed"
                    }
                },
                errorPlacement: function (error, element) {
                    $("#boardError").html(error);
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
