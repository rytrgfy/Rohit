<?php
include "dbconn.php"; // Start session
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$user_id = $_SESSION['user_id'];
$email = $_SESSION['username'];

$sql = "SELECT * FROM signup WHERE id = $user_id";
$result = $conn->query($sql);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            padding: 20px;
        }

        /* Header section with navigation */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 24px;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: #e74c3c;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #e74c3c;
            color: white;
        }

        /* Profile container */
        .profile-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Profile table */
        .profile-table {
            width: 100%;
            border-collapse: collapse;
        }

        .profile-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .photo-cell {
            width: 150px;
            vertical-align: top;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .info-cell {
            vertical-align: top;
            padding: 20px;
        }

        /* Profile photo */
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3498db;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        /* Profile information */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table tr {
            border-bottom: 1px solid #eee;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-table td {
            padding: 12px 8px;
        }

        .info-label {
            font-weight: bold;
            color: #7f8c8d;
            width: 30%;
        }

        .info-value {
            color: #2c3e50;
        }

        /* Actions section */
        .actions {
            padding: 20px;
            text-align: right;
            background-color: #f9f9f9;
        }

        .action-btn {
            display: inline-block;
            padding: 8px 15px;
            margin-left: 10px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .view-btn {
            background-color: #3498db;
        }

        .edit-btn {
            background-color: #2ecc71;
        }

        .action-btn:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {

            .profile-table,
            .info-table {
                display: block;
            }

            .profile-table tr,
            .info-table tr {
                display: block;
                margin-bottom: 15px;
            }

            .profile-table td,
            .info-table td {
                display: block;
                width: 100%;
            }

            .photo-cell {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>User Profile</h1>
        <div class="nav-links">

            <!-- <a href="forgetpassword.php">Change Password</a> -->

            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="profile-container">
        <?php while ($row = $result->fetch_assoc()) { ?>

            <table class="profile-table">
                <tr>
                    <td class="photo-cell">
                        <?php if (!empty($row['profile_photo'])): ?>

                            <img src="photos/<?php echo $row['profile_photo']; ?>" class="profile-photo" alt="Profile Photo">
                        <?php else: ?>
                            <div class="profile-photo"
                                style="background-color: #ccc; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 40px; color: #fff;">?</span>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="info-cell">
                        <table class="info-table">
                            <tr>
                                <td class="info-label">Name:</td>
                                <td class="info-value"><?php echo $row['name']; ?></td>
                            </tr>
                            <tr>
                                <td class="info-label">Contact:</td>
                                <td class="info-value"><?php echo $row['contact']; ?></td>
                            </tr>
                            <tr>
                                <td class="info-label">Address:</td>
                                <td class="info-value"><?php echo $row['address']; ?></td>
                            </tr>
                            <tr>
                                <td class="info-label">City:</td>
                                <td class="info-value"><?php echo $row['city']; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="actions">
                <form action="handle_action.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="action" value="tasklist" class="action-btn view-btn">Add task</button>

                    <button type="submit" name="action" value="change_password" class="action-btn view-btn">Change
                        Password</button>
                    <button type="submit" name="action" value="view_profile" class="action-btn view-btn">View
                        Profile</button>
                    <button type="submit" name="action" value="edit_profile" class="action-btn edit-btn">Edit
                        Profile</button>
                </form>
            </div>

        <?php } ?>
    </div>
</body>

</html>