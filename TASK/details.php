<?
include "dbconn.php";

$id = $_GET['id'] ?? null; // Check if ID is set

if (!$id) {
    die("failed to get id.");
}

$sql_fetch_query = "SELECT * FROM task1 WHERE id = $id";
$result = $conn->query($sql_fetch_query);

if ($result->num_rows == 0) {
    die("user not found with id");
}

$i = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        body {
            background-color:rgb(59, 57, 57);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 20px;
            text-align: center;
        }

        .profile-img {
            width: 300px;
            height: 300px;
            /* border-radius: 50%; */
            border: 2px solid #ddd;
        }

        h2 {
            color: #333;
        }

        p {
            font-size: 16px;
            margin: 8px 0;
            color: #555;
        }

        .back-btn {
            display: inline-block;
            /* margin-top: 15px;
            padding: 8px 15px;
            text-decoration: none; */
            background: rgb(0, 0, 0);
            color: white;
            border-radius: 5px;
            font-size: 14px;
            
        }

        .back-btn:hover {
            background: rgb(5, 114, 223);
        }
    </style>
</head>

<body>

    <div class="container">
        <?php if (!empty($i['photo'])): ?>
            <img src="uploads/<?php echo $i['photo']; ?>" class="profile-img" alt="Profile Photo">
        <?php endif; ?>

        <h2><?php echo $i['name']; ?></h2>
        <p><strong>Mobile:</strong> <?php echo $i['mobile']; ?></p>
        <p><strong>Email:</strong> <?php echo $i['email']; ?></p>
        <p><strong>Address:</strong> <?php echo $i['address']; ?></p>
        <p><strong>Age:</strong> <?php echo $i['age']; ?></p>
        <p><strong>Gender:</strong> <?php echo $i['gender']; ?></p>

        <a href="index.php" class="back-btn">Go Back</a>
    </div>

</body>

</html>