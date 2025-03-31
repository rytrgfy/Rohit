<?php
include "dbconn.php";
$id = $_GET['id'];
// echo $id;
if (!isset($id)) {
    header("Location: index.html");
    exit();
}

$sql = "SELECT * FROM task WHERE id = '$id'";
$query = $conn->query($sql);
$res = $query->fetch_assoc();


$title = $description = $status = "";

// Process the form when submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $update_sql = "UPDATE task SET title = '$title', description = '$description', status = '$status' WHERE id = '$id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Task updated successfully'); window.location.href='tasklist.php';</script>";
    } else {
        echo "Error updating task: " . $conn->error;
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, rgb(1, 19, 46) 0%, #c3cfe2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Form Container */
        .container {
            width: 70vw;
            background-color: #c3cfe2;
            /* max-width: 100%; */
            /* max-width: 4px; */
            padding: 20px;
        }

        /* Form Box */
        .task-form {
            background:rgb(135, 133, 133);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease-in-out;
        }

        /* .task-form:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        } */

        /* Heading */
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* Labels */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #34495e;
        }

        /* Inputs & Textarea */
        input[type="text"],
        textarea,
        select {
            width: 90%; 
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 2px solid rgb(148, 142, 142);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
            text-align: center;
        }

        /* Focus Effect */
        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        /* Button */
        button {
            width: 100%;
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background: linear-gradient(90deg, #2980b9 0%, #1f6aa5 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        /* Textarea */
        textarea {
            /* resize: vertical;
            resize: horizontal; */
            min-height: 100px;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .task-form {
                margin: 20px;
                padding: 20px;
            }
        }

        /* Error Styling */
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



        <form action="" method="POST" id="taskForm" class="task-form">
            <h2>Edit Task</h2>
            <input type="hidden" name="submit_task" value="1">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title"
                value="<?php echo htmlspecialchars($res['title'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description"
                required><?php echo htmlspecialchars($res['description'], ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Pending" <?php echo ($res['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="In Progress" <?php echo ($res['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress
                </option>
                <option value="Completed" <?php echo ($res['status'] == 'Completed') ? 'selected' : ''; ?>>Completed
                </option>
            </select><br><br>

            <button type="submit">Update Task</button>
        </form>
    </div>



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


</body>

</html>