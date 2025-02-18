<?php
$con = mysqli_connect('localhost', 'root', '', 'Todo');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'title' input is empty
    if (empty($_POST['Title'])) {
        echo '<script>alert("Enter something!")</script>';
    } else {
        // Display the entered title and text in a bullet list
        echo "<ul>";
        echo "<li><strong>Title:</strong> " . htmlspecialchars($_POST['Title']) . "</li>";
        echo "<li><strong>Text:</strong> " . htmlspecialchars($_POST['text']) . "</li>";
        echo "</ul>";
    }
}

$txtTitle = isset($_POST['Title']) ? $_POST['Title'] : '';
$txtText = isset($_POST['text']) ? $_POST['text'] : '';

// Prepare the SQL statement
$sql = "INSERT INTO `Todo` (`Title`, `text`) VALUES (?, ?)";

$stmt = mysqli_prepare($con, $sql);

if ($stmt === false) {
    die("Error preparing statement: " . mysqli_error($con));
}

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "ss", $txtTitle, $txtText);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    header("Location: index.html");
    exit();

} else {
    echo "Error: " . mysqli_error($con);
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($con);

?>