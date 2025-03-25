<?php
include "dbconn.php";
session_start();

// Ensure session contains user_id
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$id = $_SESSION['user_id'];
// echo $id;

// Ensure 'type' is set before using it
$type = isset($_POST['type']) ? $_POST['type'] : '';
$stateId = isset($_POST['stateId']) ? intval($_POST['stateId']) : 0;
$districtId = isset($_POST['districtId']) ? intval($_POST['districtId']) : 0;

// Fetch user data when needed
if (in_array($type, ["state", "district", "city"])) {
    $fetch_data_sql = "SELECT signup.name, signup.contact, signup.address,
                              signup.state AS state_id, state.state_name, 
                              signup.dist AS district_id, district.district_name, 
                              signup.city AS city_id, city.city_name 
                       FROM signup 
                       LEFT JOIN state ON signup.state = state.id 
                       LEFT JOIN district ON signup.dist = district.id 
                       LEFT JOIN city ON signup.city = city.id 
                       WHERE signup.id = $id";

    $result = $conn->query($fetch_data_sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    $data = $result->fetch_assoc();
}

// Fetch states
if ($type == "state") {
    $sql = "SELECT * FROM state ORDER BY state_name ASC";
    $query = $conn->query($sql);

    if (!$query) {
        die("Error fetching states: " . $conn->error);
    }

    echo "<option value=''>Select State</option>";
    while ($row = $query->fetch_assoc()) {
        $selected = (isset($data['state_id']) && $row['id'] == $data['state_id']) ? 'selected' : '';
        echo "<option value='{$row['id']}' $selected>{$row['state_name']}</option>";
    }
    exit;
}

// Fetch districts
if ($type == "district" && $stateId > 0) {
    $sql = "SELECT * FROM district WHERE state_id = $stateId ORDER BY district_name ASC";
    $query = $conn->query($sql);

    if (!$query) {
        die("Error fetching districts: " . $conn->error);
    }

    echo "<option value=''>Select District</option>";
    while ($row = $query->fetch_assoc()) {
        $selected = (isset($data['district_id']) && $row['id'] == $data['district_id']) ? 'selected' : '';
        echo "<option value='{$row['id']}' $selected>{$row['district_name']}</option>";
    }
    exit;
}

// Fetch cities
if ($type == "city" && $districtId > 0) {
    $sql = "SELECT * FROM city WHERE district_id = $districtId ORDER BY city_name ASC";
    $query = $conn->query($sql);

    if (!$query) {
        die("Error fetching cities: " . $conn->error);
    }

    echo "<option value=''>Select City</option>";
    while ($row = $query->fetch_assoc()) {
        $selected = (isset($data['city_id']) && $row['id'] == $data['city_id']) ? 'selected' : '';
        echo "<option value='{$row['id']}' $selected>{$row['city_name']}</option>";
    }
    exit;
}

// Fetch academic details
if ($type == "academic") {
    $academicQuery = "SELECT * FROM academic_details WHERE signup_id = $id";
    $academicResult = $conn->query($academicQuery);

    if (!$academicResult) {
        die("Error fetching academic details: " . $conn->error);
    }

    $academicData = [];
    while ($row = $academicResult->fetch_assoc()) {
        $academicData[] = $row;
    }
    echo json_encode($academicData);
    exit;
}

// Fetch board dropdown options

if ($type == "board") {
    // Fetch the previously selected board for the user
    $userBoardQuery = "SELECT board_name FROM boards WHERE id = (SELECT board_id FROM signup WHERE id = $id)";
    $userBoardResult = $conn->query($userBoardQuery);

    $selectedBoard = '';
    if ($userBoardResult && $userBoardResult->num_rows > 0) {
        $selectedBoardRow = $userBoardResult->fetch_assoc();
        $selectedBoard = $selectedBoardRow['board_name'];
    }

    // Fetch all boards for the dropdown
    $sql = "SELECT board_name FROM boards ORDER BY board_name ASC";
    $query = $conn->query($sql);

    if (!$query) {
        die("Error fetching boards: " . $conn->error);
    }

    echo "<option value=''>-- Select Board --</option>";
    while ($row = $query->fetch_assoc()) {
        $selected = ($row['board_name'] == $selectedBoard) ? 'selected' : '';
        echo "<option value='{$row['board_name']}' $selected>{$row['board_name']}</option>";
    }
    exit;
}

?>