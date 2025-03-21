<?php
include "dbconn.php";

$type = $_POST['type'];

if ($type == "state") {
    $sql = "SELECT * FROM state";
    $query = mysqli_query($conn, $sql);
    echo "<option value=''>Select state</option>";
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['state_name']}</option>";
    }
} elseif ($type == "district") {
    $sql = "SELECT * FROM district WHERE state_id = {$_POST['id']}";
    $query = mysqli_query($conn, $sql);
    echo "<option value=''>Select District</option>";
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['district_name']}</option>";
    }
} elseif ($type == "city") {
    $sql = "SELECT * FROM city WHERE district_id = {$_POST['id']}";
    $query = mysqli_query($conn, $sql);
    echo "<option value=''>Select City</option>";
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['city_name']}</option>";
    }
} elseif ($type == "boards") {
    // Get already selected boards if provided
    $selected_boards = isset($_POST['selected']) ? $_POST['selected'] : [];
    $current_board = isset($_POST['current_board']) ? $_POST['current_board'] : '';

    $sql = "SELECT * FROM boards";
    $query = mysqli_query($conn, $sql);

    // Add default option but don't select it if we have a current board
    if (empty($current_board)) {
        echo "<option value='' selected>Select Board</option>";
    } else {
        echo "<option value=''>Select Board</option>";
    }

    while ($row = mysqli_fetch_assoc($query)) {
        // Skip this board if it's already selected in another dropdown
        if (in_array($row['id'], $selected_boards) && $row['id'] != $current_board) {
            continue;
        }

        // Mark as selected if it matches current_board
        $selected = ($row['id'] == $current_board) ? 'selected' : '';

        echo "<option value='{$row['id']}' $selected>{$row['board_name']}</option>";
    }
}
?>