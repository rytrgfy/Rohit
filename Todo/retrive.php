<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
            margin: 0;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .todo-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        .todo-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .todo-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .todo-id {
            font-weight: bold;
            font-size: 1.1em;
            color: #333;
        }

        .todo-title {
            font-size: 1.2em;
            color: #4CAF50;
            margin: 5px 0;
        }

        .todo-text {
            font-size: 1em;
            color: #555;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <h1>Your Todos</h1>
    <div class="todo-container">
        <?php
        // Database connection
        $con = mysqli_connect('localhost', 'root', '', 'Todo');
        
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Delete operation
        if (isset($_GET['delete_id'])) {
            $delete_id = $_GET['delete_id'];
            $delete_sql = "DELETE FROM todo WHERE id = $delete_id";
            if (mysqli_query($con, $delete_sql)) {
                echo "<script>alert('Todo deleted successfully!'); window.location.href='retrive.php';</script>";
            } else {
                echo "<script>alert('Error deleting todo');</script>";
            }
        }
        
        // Query to fetch data
        $sql = "SELECT id, Title, text FROM todo";
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            // Output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='todo-item'>
                        <div>
                            <div class='todo-id'>ID: " . htmlspecialchars($row['id']) . "</div>
                            <div class='todo-title'>" . htmlspecialchars($row['Title']) . "</div>
                            <div class='todo-text'>" . htmlspecialchars($row['text']) . "</div>
                        </div>
                        <a href='?delete_id=" . $row['id'] . "'><button class='delete-btn'>Delete</button></a>
                      </div>";
            }
        } else {
            echo "<div class='todo-item'>No records found</div>";
        }
        
        // Close connection
        mysqli_close($con);
        ?>
    </div>
</body>
</html>
