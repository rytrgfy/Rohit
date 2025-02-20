<?php
$name = $dob = $email = $address = $number = $gender = $photoupload = $course = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $number = $_POST["number"];
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

echo "Name: $name <br/>";
echo "DOB: $dob <br/>";
echo "Email: $email <br/>";
echo "Address: $address <br/>";
echo "number No: $number <br/>";
echo "Gender: $gender <br/>";
echo "Course: $course <br/>";
echo "Photo: " . ($photoPath !== "No file uploaded" ? "<br/><img src='$photoPath' width='200' alt='Uploaded Photo'/>" : "No file uploaded") . "<br/>";
?>