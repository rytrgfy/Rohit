<?php
$nameerr = $emailerr = $websiteerr = $commenterr = $genderserr = "";
$name = $email = $website = $comment = $gender = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Name validation
    if (empty($_POST["name"])) {
        $nameerr = "Name is required";
    } else {
        $name = trim($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameerr = "Only letters and white space allowed";
        }
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailerr = "Email is required";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailerr = "Invalid email format";
        }
    }

    // Website validation
    if (empty($_POST["website"])) {
        $websiteerr = "Website is required";
    } else {
        $website = trim($_POST["website"]);
        if (!preg_match("/\b(?:https?|ftp):\/\/[-a-zA-Z0-9+&@#\/%?=~_|!:,.;]*[-a-zA-Z0-9+&@#\/%=~_|]/", $website)) {
            $websiteerr = "Invalid URL";
        }
    }

    // Comment validation (optional)
    if (!empty($_POST["comment"])) {
        $comment = trim($_POST["comment"]);
    }

    // Gender validation
    if (empty($_POST["gender"])) {
        $genderserr = "Gender is required";
    } else {
        $gender = $_POST["gender"];
    }

    // If no errors, display the data
    if (empty($nameerr) && empty($emailerr) && empty($websiteerr) && empty($genderserr)) {
        echo "<h3>Form Submitted Successfully</h3>";
        echo "Your Name: " . htmlspecialchars($name) . "<br>";
        echo "Your Email: " . htmlspecialchars($email) . "<br>";
        echo "Your Website: " . htmlspecialchars($website) . "<br>";
        echo "Your Comment: " . htmlspecialchars($comment) . "<br>";
        echo "Your Gender: " . htmlspecialchars($gender) . "<br>";
    }
}
?>
