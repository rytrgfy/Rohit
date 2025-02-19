<?php include "main.php";?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic form</title>
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

    <form method="post" action="main.php">
        <span id="nameError"><?php echo $nameerr;?></span>
        <br><br>
        Name: <input type="text" name="name">

        <span id="emailError"><?php echo $emailerr;?></span>
        <br><br>
        E-mail: <input type="text" name="email">


        <span id="websiteError"><?php echo $websiteerr;?></span>
        <br><br>
        Website: <input type="text" name="website">


        <span id="CommentError"><?php echo $commenterr;?></span>
        <br><br>
        Comment: <textarea name="comment" rows="5" cols="40"></textarea>


        <span id="GenderError"><?php echo $genderserr;?></span>
        <br><br>
        Gender:
        <input type="radio" name="gender" value="female">Female
        <input type="radio" name="gender" value="male">Male
        <input type="radio" name="gender" value="other">Other
        <br><br>
        <input type="submit" name="submit" value="Submit">
    </form>




    
</body>

</html>