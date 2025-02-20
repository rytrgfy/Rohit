<?php include "main.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        form {
            width: 90%;
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .error {
            color: red;
            font-size: 12px;
        }

        input[type="submit"] {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #218838;
        }
    </style>
</head>

<body>
    <form method="post" action="index.php">
        <span class="error"><?php echo $nameerr; ?></span><br>
        Name: <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>"><br>

        <span class="error"><?php echo $emailerr; ?></span><br>
        E-mail: <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>

        <span class="error"><?php echo $websiteerr; ?></span><br>
        Website: <input type="text" name="website" value="<?php echo htmlspecialchars($website); ?>"><br>

        <span class="error"><?php echo $commenterr; ?></span><br>
        Comment: <textarea name="comment" rows="5"><?php echo htmlspecialchars($comment); ?></textarea><br>

        <span class="error"><?php echo $genderserr; ?></span><br>
        Gender:
        <input type="radio" name="gender" value="female" <?php if ($gender == "female") echo "checked"; ?>> Female
        <input type="radio" name="gender" value="male" <?php if ($gender == "male") echo "checked"; ?>> Male
        <input type="radio" name="gender" value="other" <?php if ($gender == "other") echo "checked"; ?>> Other
        <br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>

</html>
