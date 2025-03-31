<?php
include "dbconn.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $sql = "INSERT INTO task (title, description, status, user_id) VALUES ('$title', '$description', '$status', '$id')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Task assigned successfully'); window.location.href='tasklist.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 80%;
            padding: 20px;
        }

        .task-form {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        /* .task-form:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        } */

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 600;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: #34495e;
            transition: color 0.3s ease;
        }

        input[type="text"],
        textarea,
        select {
            width: 95%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 2px solid rgb(135, 214, 234);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
            background-color: #f9f9f9;
            color: #34495e;
            font-weight: 500;
            text-align: center;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color:rgb(201, 232, 108);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        button {
            width: 100%;
            background-color:rgb(96, 240, 237);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background-color:rgb(92, 179, 102);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
            color: solid white;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        @media (max-width: 480px) {
            .task-form {
                margin: 20px;
                padding: 20px;
            }
        }

        .error {
            color: red;
            font-size: 14px;
        }

        input.error,
        textarea.error,
        select.error {
            border: 2px solid red;
        }
    </style>
</head>


<body>
    <div class="container">
        <h2>Assign Task</h2><br>
        <form id="taskForm" class="task-form" action="" method="POST">
            <input type="hidden" name="submit_task" value="1">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea><br>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select><br>

            <button type="submit">Add Task</button>
        </form>
    </div>
</body>

<script>
    $(document).ready(function () {
        $("#taskForm").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 3
                },
                description: {
                    required: true,
                    minlength: 5
                },
                status: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Please enter a title",
                    minlength: "Title must be at least 3 characters"
                },
                description: {
                    required: "Please enter a description",
                    minlength: "Description must be at least 5 characters"
                },
                status: {
                    required: "Please select a status"
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    });
</script>








</html>