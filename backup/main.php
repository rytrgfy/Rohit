<?php
$name = $dob = $email = $address = $number = $gender = $photoPath = $course = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $dob = $_POST["dob"];
    $email = htmlspecialchars($_POST["email"]);
    $address = htmlspecialchars($_POST["address"]);
    $number = htmlspecialchars($_POST["number"]);
    $gender = $_POST["gender"];
    $course = $_POST["course"];

    // Handling File Upload
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $photoupload = basename($_FILES["photo"]["name"]);
        $targetFilePath = $uploadDir . $photoupload;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
            $photoPath = $targetFilePath;
        } else {
            $photoPath = "No file uploaded";
        }
    } else {
        $photoPath = "No file uploaded";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Student Registration Details</h2>
        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>DOB:</strong> <?php echo $dob; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Address:</strong> <?php echo $address; ?></p>
        <p><strong>Phone No:</strong> <?php echo $number; ?></p>
        <p><strong>Gender:</strong> <?php echo ucfirst($gender); ?></p>
        <p><strong>Course:</strong> <?php echo strtoupper($course); ?></p>
        <p><strong>Photo:</strong></p>
        <?php if ($photoPath !== "No file uploaded"): ?>
            <img src="<?php echo $photoPath; ?>" width="200" alt="Uploaded Photo"/>
        <?php else: ?>
            <p>No file uploaded</p>
        <?php endif; ?>
    </div>
</body>
</html>
