<?php
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach (["firstname", "lastname", "address", "emailaddress", "password", "gender"] as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = "* Required";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylish Form Processing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin-bottom: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .radio-group {
            display: flex;
            gap: 15px;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        button {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .output-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stylish Form</h1>
        <form method="post">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="firstname">
                <span class="error"><?php echo $errors['firstname'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="lastname">
                <span class="error"><?php echo $errors['lastname'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address">
                <span class="error"><?php echo $errors['address'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="emailaddress">
                <span class="error"><?php echo $errors['emailaddress'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password">
                <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label>Gender:</label>
                <div class="radio-group">
                    <input type="radio" name="gender" value="Male"> Male
                    <input type="radio" name="gender" value="Female"> Female
                </div>
                <span class="error"><?php echo $errors['gender'] ?? ''; ?></span>
            </div>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
        echo '<div class="output-container">';
        echo '<h1>Input Received</h1>';
        echo '<table>';
        echo '<thead><tr><th>Parameter</th><th>Value</th></tr></thead><tbody>';
        foreach ($_POST as $key => $value) {
            if ($key != 'submit' && $key != 'password') {
                echo '<tr><td>' . ucfirst($key) . '</td><td>' . htmlspecialchars($value) . '</td></tr>';
            }
        }
        echo '</tbody></table>';
        echo '</div>';
    }
    ?>
</body>
</html>
