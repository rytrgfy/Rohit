<?php
include "dbconn.php";
session_start();

$name = $mobile = $email = $address = $age = $gender = $photo = "";

$editvalues = false;

$i = [
    'name' => '',
    'mobile' => '',
    'email' => '',
    'address' => '',
    'age' => '',
    'gender' => '',
    'photo' => ''
];

$id = [];
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $editvalues = true;

    echo "editing page";
    // Fetch data from database 
    $sql_fetch_query = "SELECT * FROM task1 WHERE id = $id";
    $result = $conn->query($sql_fetch_query);

    if ($result->num_rows > 0) {
        $i = $result->fetch_assoc();
        $name = $i['name'];
        $mobile = $i['mobile'];
        $email = $i['email'];
        $address = $i['address'];
        $age = $i['age'];
        $gender = $i['gender'];
        $photo = $i['photo'];
        // echo "record found";
    } else {
        echo "Record not found!";
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    // file upload
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
    } else {
        $photo = isset($_POST['old_photo']) ? $_POST['old_photo'] : '';
    }


    if ($editvalues) {
        echo $sql_update_query = "UPDATE task1 SET 
            name='$name', mobile='$mobile', email='$email', address='$address', 
            age=$age, gender='$gender', photo='$photo' 
            WHERE id=$id";
        if ($conn->query($sql_update_query) === TRUE) {
            // echo "Data Updated successfully";
            echo "<script>alert('Data Updated successfully');window.location.href='index.php';</script>";
            exit();
        } else {
            echo "Error submitting: " . $conn->error;
        }
    } else {
        $sql_insert_query = "INSERT INTO task1 (name, mobile, email, address, age, gender, photo) 
            VALUES ('$name', '$mobile', '$email', '$address', '$age','$gender', '$photo');";


        if ($conn->query($sql_insert_query) === TRUE) {
            echo "<script>window.location.href = 'index.php'</script>";

            exit();
        } else {
            echo "Error submitting: " . $conn->error;
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>

    <style>
        body {
            background-color: ghostwhite;
        }

        h3 {
            text-align: center;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .input {
            width: 300px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
        }

        input,
        textarea {
            width: 100%;
            margin: 5px 0;
            padding: 5px;
        }

        .gender-options {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        input[type="submit"] {
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>

</head>

<body>
    <h3>Input Your Data</h3>
    <div class="input">
        <form id="myform" action="" method="post" enctype="multipart/form-data">


            Name: <input type="text" name="name" value="<?php echo $i['name']; ?>">
            <br><br>
            Mobile: <input type="tel" name="mobile" value="<?php echo $i['mobile']; ?>">
            <br><br>
            Email: <input type="email" name="email" value="<?php echo $i['email']; ?>">
            <br><br>
            Address: <textarea name="address" rows="4" cols="50"><?php echo $i['address']; ?></textarea>
            <br><br>
            Age: <input type="number" name="age" value="<?php echo $i['age']; ?>">
            <br><br>


            <div class="gender-options">
                Gender:
                <input type="radio" name="gender" value="Male" <?php if (isset($i['gender']) && $i['gender'] == "Male")
                    echo "checked"; ?>> male
                <input type="radio" name="gender" value="Female" <?php if (isset($i['gender']) && $i['gender'] == "Female")
                    echo "checked"; ?>> Female
                <input type="radio" name="gender" value="Others" <?php if (isset($i['gender']) && $i['gender'] == "Others")
                    echo "checked"; ?>> Others

                <br><br>
            </div>

            Photo:
            <?php if (!empty($photo)): ?>
                <br>
                <img src="uploads/<?php echo $photo; ?>" alt="Previous Photo" width="100">
                <br>
                <input type="hidden" name="old_photo" value="<?php echo $photo; ?>">
            <?php endif; ?>

            <input type="file" name="photo">
            <br><br>

            <input type="submit" name="submit" value="Submit">
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="app.js"></script>





</body>

</html>