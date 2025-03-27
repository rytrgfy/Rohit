<?php
session_start();

if (!isset($_POST['id']) || !isset($_POST['action'])) {
    die("Invalid request.");
}

$id = $_POST['id'];
$encrypted_id = base64_encode($id); // Encode the ID

$action = $_POST['action'];
switch ($action) {
    case 'change_password':
        header("Location: forgetpassword.php?id=" . $encrypted_id);
        break;
    case 'view_profile':
        header("Location: view_profile.php?id=" . $encrypted_id);
        break;
    case 'edit_profile':
        header("Location: edit_profile.php?id=" . $encrypted_id);
        break;
    default:
        die("Unknown action.");
}

exit();
?>
