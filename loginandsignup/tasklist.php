<?php
include "dbconn.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

// Fetch tasks from the database
// $sql = "SELECT * FROM task WHERE user_id = '$id' ordert by id desc";
$sql = "SELECT * FROM task WHERE user_id = '$id' ORDER BY id DESC"; // Corrected SQL query
$query = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE task SET status = '$status' WHERE id = '$task_id'";

    if ($conn->query($update_sql)) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . $conn->error;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 5px 10px;
            cursor: pointer;
        }

        .edit {
            background-color: #ffc107;
            border: none;
        }

        .completed-row {
            background-color:rgb(198, 232, 174);
            color: gray;
        }

        .status-dropdown:disabled,
        .edit:disabled {
            cursor: not-allowed;
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <h2>Task List</h2>

    <form action="assigntask.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <button type="submit">Add Task</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th> <!-- Separate column for status update & edit -->
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; ?>
            <?php if ($query->num_rows > 0): ?>
                <?php while ($row = $query->fetch_assoc()): ?>
                    <tr class="<?php echo ($row['status'] == 'Completed') ? 'completed-row' : ''; ?>">
                        <td><?php echo $count++; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td> <!-- Status remains visible -->

                        <td>
                            <!-- Status Update Dropdown -->
                            <select class="status-dropdown" data-task-id="<?php echo $row['id']; ?>" <?php echo ($row['status'] == 'Completed') ? 'disabled' : ''; ?>>
                                <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="In Progress" <?php echo ($row['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Completed" <?php echo ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>

                            <!-- Edit Button -->
                            <a href="edit_task.php?id=<?php echo $row['id']; ?>">
                                <button class="action-btn edit" <?php echo ($row['status'] == 'Completed') ? 'disabled' : ''; ?>>Edit</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No tasks assigned.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            $(".status-dropdown").change(function () {
                var taskId = $(this).data("task-id");
                var newStatus = $(this).val();
                var dropdown = $(this);
                var row = dropdown.closest("tr");

                $.ajax({
                    url: window.location.href,
                    type: "POST",
                    data: { task_id: taskId, status: newStatus },
                    success: function (response) {
                        alert(response);

                        // Update the status column text dynamically
                        row.find("td:nth-child(4)").text(newStatus);

                        if (newStatus === 'Completed') {
                            row.addClass("completed-row");
                            dropdown.prop('disabled', true);
                            row.find(".edit").prop('disabled', true);
                        } else {
                            row.removeClass("completed-row");
                            dropdown.prop('disabled', false);
                            row.find(".edit").prop('disabled', false);
                        }
                    },
                    error: function () {
                        alert("Failed to update status.");
                    }
                });
            });
        });
    </script>

</body>

</html>
