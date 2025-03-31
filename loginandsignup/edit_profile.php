<?php
include 'dbconn.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Check if ID is provided and decode it
if (isset($_GET['id'])) {
    $id = base64_decode($_GET['id']);

    // Validate decoded ID (must be a number)
    if (!is_numeric($id) || $id <= 0) {
        die("Invalid ID.");
    }
    if($id == 78){
        header("Location: admin.php");
        exit();
    }
} else {
    die("ID not provided.");
}


// Fetch user data
$fetch_data_sql = "SELECT 
    signup.username, 
    signup.password, 
    signup.name, 
    signup.contact, 
    signup.address, 
    signup.profile_photo, 
    state.state_name, 
    state.id AS state_id, 
    district.id AS district_id, 
    district.district_name, 
    city.id AS city_id, 
    city.city_name, 
    boards.board_name,
    academic_details.board, 
    academic_details.courses, 
    academic_details.total_marks, 
    academic_details.secured_marks, 
    academic_details.percentage, 
    academic_details.reference_file 
FROM signup 
JOIN academic_details ON signup.id = academic_details.signup_id 
JOIN boards ON academic_details.board = boards.id  
JOIN state ON signup.state = state.id 
JOIN district ON signup.dist = district.id 
JOIN city ON signup.city = city.id 
WHERE signup.id = $id"; // No quotes needed around integer

$result = $conn->query($fetch_data_sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No data found for the provided ID.");
}
// print_r($data);



// $academicData = [];
// while ($row = $result->fetch_assoc()) {
//     $academicData[] = $row;
// }

// echo $data['name'];
// echo $data['contact'];
// echo $data['address'];
// echo $data['state_name'] . " (ID: " . $data['state_id'] . ")<br>";
// echo $data['district_name'] . " (ID: " . $data['district_id'] . ")<br>";
// echo $data['city_name'] . " (ID: " . $data['city_id'] . ")<br>";
// echo $data['profile_photo'];
// echo $data['state_name'];
// echo $data['district_name'];
// echo $data['city_name'];
// echo $data['board_name'];
// echo $data['courses'];
// echo $data['total_marks'];
// echo $data['secured_marks'];
// echo $data['percentage'];
// echo $data['reference_file'];




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $profile_photo = $_FILES['profile_photo']['name'];
    $profile_photo_tmp = $_FILES['profile_photo']['tmp_name'];
    $previous_profile_photo = $_POST['previous_profile_photo'];
    $academic = $_POST['academic'];
    $referenceFiles = $_FILES['referenceFiles'];
    $previousReferenceFiles = $_POST['previousReferenceFiles'];

    

    // Update user data
    $update_user_sql = "UPDATE signup SET name = '$name', contact = '$contact', address = '$address', state = '$state', dist = '$district', city = '$city'";

    // Update profile photo if provided
    if (!empty($profile_photo)) {
        // Delete previous photo if exists
        if (!empty($previous_profile_photo)) {
            unlink("photos/" . $previous_profile_photo);
        }

        // Upload new photo
        $profile_photo = uniqid() . '_' . $profile_photo;
        move_uploaded_file($profile_photo_tmp, "photos/$profile_photo");

        $update_user_sql .= ", profile_photo = '$profile_photo'";
    } else {
        // Keep the old profile photo if no new one is uploaded
        $profile_photo = $previous_profile_photo;
    }

    $update_user_sql .= " WHERE id = $id";

    if ($conn->query($update_user_sql)) {
        // Delete existing academic details
        $delete_academic_sql = "DELETE FROM academic_details WHERE signup_id = $id";
        if ($conn->query($delete_academic_sql)) {
            // Re-insert academic details
            if (!empty($academic)) {
                foreach ($academic as $index => $record) {
                    $boardId = $record['boardId'];
                    $courseName = $record['courseName'];
                    $totalMarks = $record['totalMarks'];
                    $securedMarks = $record['securedMarks'];
                    $percentageScore = $record['percentageScore'];

                    // Handle reference files
                    $uploadedFiles = [];
                    if (!empty($referenceFiles['name'][$index][0])) {
                        foreach ($referenceFiles['name'][$index] as $fileIndex => $fileName) {
                            $fileTmp = $referenceFiles['tmp_name'][$index][$fileIndex];
                            $newFileName = uniqid() . '_' . $fileName;
                            move_uploaded_file($fileTmp, "file_uploads_data/$newFileName");
                            $uploadedFiles[] = $newFileName;
                        }
                    }

                    // Use old files if no new files are uploaded
                    $finalFiles = !empty($uploadedFiles) ? implode(',', $uploadedFiles) : $previousReferenceFiles[$index];

                    // Insert new academic record
                    $insert_academic_sql = "INSERT INTO academic_details (signup_id, board, courses, total_marks, secured_marks, percentage, reference_file) 
                                            VALUES ($id, $boardId, '$courseName', $totalMarks, $securedMarks, $percentageScore, '$finalFiles')";
                    if (!$conn->query($insert_academic_sql)) {
                        echo "<script>alert('Error updating academic details: {$conn->error}');</script>";
                    }
                }
            }
        }
        echo "<script>alert('Profile updated successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating profile: {$conn->error}');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        /* Global Styles and Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f8fa;
            color: #333;
            line-height: 1.6;
        }

        /* Main Container */
        .main {
            max-width: 1000px;
            margin: 30px auto;
            padding: 25px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Form Sections */
        form h3 {
            padding: 10px 0;
            margin: 20px 0 15px;
            color: #2c3e50;
            font-size: 1.5rem;
            border-bottom: 2px solid #3498db;
            position: relative;
        }

        /* Basic Info Layout */
        .basicinfodivs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }

        /* Academic Details */
        #academic-container {
            margin-bottom: 20px;
        }

        .academic-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Input Styles */
        input[type="text"],
        input[type="tel"],
        input[type="number"],
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* File Inputs */
        input[type="file"] {
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 6px;
            background-color: #f8f9fa;
            width: 100%;
        }

        input[type="file"]:hover {
            background-color: #eef2f7;
        }

        /* Login Details Section */
        .logindetails {
            max-width: 500px;
            margin: 0 auto;
        }

        /* Buttons */
        #add-academic {
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 24px;
            cursor: pointer;
            margin: 10px 0 20px;
            display: block;
            line-height: 40px;
            text-align: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            right: 200px;
            position: absolute;
        }

        #add-academic:hover {
            background-color: #2ecc71;
            transform: scale(1.05);
        }

        .remove-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 15px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .remove-btn:hover {
            background-color: #c0392b;
        }

        /* Submit Button */
        .submit-container {
            text-align: center;
            margin-top: 30px;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 30px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
        }

        /* Readonly fields */
        input[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main {
                padding: 15px;
                margin: 15px;
            }

            .basicinfodivs {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            input[type="submit"] {
                width: 100%;
            }
        }

        /* Animations for smoother UX */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .academic-section {
            animation: fadeIn 0.3s ease-out;
        }

        /* Additional Styling */
        .percentage-field {
            font-weight: bold;
            color: #2c3e50;
        }

        /* Validation Styles */
        input:invalid {
            border-color: #e74c3c;
        }

        /* Optional: Custom Checkbox and Radio Styles */
        input[type="checkbox"],
        input[type="radio"] {
            margin-right: 8px;
        }

        /* Make placeholder text lighter */
        ::placeholder {
            color: #aaa;
            opacity: 1;
        }

        .err_msg {
            color: red !important;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="main">
        <form id="signupForm" action="" method="post" enctype="multipart/form-data">
            <div class="basicinfo">
                <h3>Basic Info</h3>
                <div class="basicinfodivs">
                    <div class="form-group name">
                        <label>Name:</label>
                        <input type="text" value='<?php echo $data['name'] ?>' name="name"
                            placeholder="Enter your name">
                    </div>

                    <div class="form-group contact">
                        <label>Contact:</label>
                        <input type="tel" value='<?php echo $data['contact']; ?>' name="contact"
                            placeholder="Enter your mobile number">
                    </div>

                    <div class="form-group address">
                        <label>Address:</label>
                        <textarea name="address" id="address" cols="9"
                            rows="3"><?php echo $data['address']; ?></textarea>
                    </div>

                    <div class="form-group state">
                        <label>Select State:</label>
                        <select name="state" id="stateId">
                            <option value="">Loading...</option>
                        </select>
                    </div>

                    <div class="form-group district">
                        <label>Select District:</label>
                        <select name="district" id="districtId">
                            <option value="">Loading...</option>
                        </select>
                    </div>

                    <div class="form-group city">
                        <label>Select City:</label>
                        <select name="city" id="cityId">
                            <option value="">Loading...</option>
                        </select>
                    </div>

                    <div class="form-group profilephoto">
                        <label>Profile Photo:</label>
                        <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*">

                        <!-- Display previously uploaded image -->
                        <div id="profilePhotoPreview">
                            <?php if (!empty($data['profile_photo'])): ?>
                                <img src="photos/<?php echo $data['profile_photo']; ?>" id="profileImage"
                                    alt="Profile Photo" width="100">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>



            <?php
            // Execute the query to fetch academic details
            $academicRecordsQuery = "SELECT
    academic_details.id AS academicRecordId,
    academic_details.board AS boardId,
    boards.board_name AS boardName,
    academic_details.courses AS courseName,
    academic_details.total_marks AS totalMarks,
    academic_details.secured_marks AS securedMarks,
    academic_details.percentage AS percentageScore,
    academic_details.reference_file AS referenceFiles
FROM academic_details
JOIN boards ON academic_details.board = boards.id
WHERE academic_details.signup_id = $id";

            $academicRecordsResult = $conn->query($academicRecordsQuery);

            if (!$academicRecordsResult) {
                die("Query failed: " . $conn->error);
            }

            // Fetch academic data
            $academicRecords = [];
            if ($academicRecordsResult->num_rows > 0) {
                while ($academicRecord = $academicRecordsResult->fetch_assoc()) {
                    $academicRecords[] = $academicRecord;
                }
            }
            ?>

            <h3>Academic Details</h3>
            <button type="button" id="add-academic">+</button>

            <div id="academic-details-container">
                <?php
                $academicIndex = 0;
                if (count($academicRecords) > 0):
                    foreach ($academicRecords as $record):
                        ?>
                        <div class="academic-container">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Board:</label>
                                    <select name="academic[<?php echo $academicIndex; ?>][boardId]" class="board-select"
                                        id="board-<?php echo $academicIndex; ?>">
                                        <option value="<?php echo $record['boardId']; ?>"><?php echo $record['boardName']; ?>
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Courses:</label>
                                    <input type="text" name="academic[<?php echo $academicIndex; ?>][courseName]"
                                        value="<?php echo $record['courseName']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Total Marks:</label>
                                    <input type="number" name="academic[<?php echo $academicIndex; ?>][totalMarks]"
                                        value="<?php echo $record['totalMarks']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Marks Secured:</label>
                                    <input type="number" name="academic[<?php echo $academicIndex; ?>][securedMarks]"
                                        value="<?php echo $record['securedMarks']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Percentage:</label>
                                    <input type="text" name="academic[<?php echo $academicIndex; ?>][percentageScore]" readonly
                                        value="<?php echo $record['percentageScore']; ?>">
                                </div>


                                <div class="form-group">
                                    <label>Reference Files:</label>
                                    <input type="file" name="referenceFiles[<?php echo $academicIndex; ?>][]" multiple>
                                    <?php if (!empty($record['referenceFiles'])): ?>
                                        <!-- for previous uploaded files hidden used -->
                                        <!-- <p>previous file</p><br>
                                         <p>👇</p> -->
                                        <input type='hidden' name='previousReferenceFiles[<?php echo $academicIndex; ?>]'
                                            value='<?php echo htmlspecialchars($record['referenceFiles']); ?>'>
                                        <div class="reference-file-preview">
                                            <?php
                                            $fileList = explode(',', $record['referenceFiles']);
                                            foreach ($fileList as $fileItem):
                                                $fileLocation = "file_uploads_data/" . htmlspecialchars($fileItem);
                                                ?>
                                                <!-- <img src="<?php //echo $fileLocation; ?>" alt="Reference File"> -->
                                                <br><a href="<?php echo $fileLocation; ?>"
                                                    target="_blank"><?php echo htmlspecialchars($fileItem); ?></a><br>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>No reference files uploaded.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- <button type="button" class="remove-btn" onclick="removeAcademicRow(this)">Remove</button> -->
                        </div>
                        <?php
                        $academicIndex++;
                    endforeach;
                else:
                    echo "<p>No academic details available. Click + to add.</p>";
                endif;
                ?>
            </div>




            <h3>Login Details</h3>
            <div class="logindetails">
                <div class="form-group username">
                    <label>Username:</label>
                    <input type="text" readonly name="username" value='<?php echo $data['username'] ?>'>
                </div>

                <div class="form-group password">
                    <label>Password:</label>
                    <input type="password" readonly name="password" value='<?php echo $data['password'] ?>'>
                </div>

                <!-- <div class="form-group cnfpassword">
                    <label>Confirm Password:</label>
                    <input type="password" name="cnfpassword" placeholder="Confirm your password">
                </div> -->
            </div>

            <div class="submit-container">
                <input type="submit" name="signup" value="update">
            </div>
            <button><a href="dashboard.php">Dashboard</a></button>
        </form>
    </div>

    <!-- Include jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load states on page load
            loadStates();
            loadAcademicDetails();

            // Event listeners for state, district, and city selection
            const stateSelect = document.getElementById('stateId');
            const districtSelect = document.getElementById('districtId');
            const citySelect = document.getElementById('cityId');

            // Fetch districts when state changes
            stateSelect.addEventListener('change', function () {
                const stateId = this.value;
                if (stateId) {
                    loadDistricts(stateId);
                }
            });

            // Fetch cities when district changes
            districtSelect.addEventListener('change', function () {
                const districtId = this.value;
                if (districtId) {
                    loadCities(districtId);
                }
            });

            // Function to load states
            function loadStates() {
                fetch('editajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'type=state'
                })
                    .then(response => response.text())
                    .then(data => {
                        stateSelect.innerHTML = data;

                        // Set selected state from PHP variable (assuming it's available in the global scope)
                        const selectedState = window.selectedState || '<?php echo $data["state_id"]; ?>';
                        stateSelect.value = selectedState;

                        // Load districts for the selected state
                        loadDistricts(selectedState);
                    })
                    .catch(error => {
                        console.error('Error loading states:', error);
                    });
            }

            // Function to load districts
            function loadDistricts(stateId) {
                if (!stateId) return;

                fetch('editajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `type=district&stateId=${stateId}`
                })
                    .then(response => response.text())
                    .then(data => {
                        districtSelect.innerHTML = data;

                        // Set selected district from PHP variable
                        const selectedDistrict = window.selectedDistrict || '<?php echo $data["district_id"]; ?>';
                        districtSelect.value = selectedDistrict;

                        // Load cities for the selected district
                        loadCities(selectedDistrict);
                    })
                    .catch(error => {
                        console.error('Error loading districts:', error);
                    });
            }

            // Function to load cities
            function loadCities(districtId) {
                if (!districtId) return;

                fetch('editajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `type=city&districtId=${districtId}`
                })
                    .then(response => response.text())
                    .then(data => {
                        citySelect.innerHTML = data;

                        // Set selected city from PHP variable
                        const selectedCity = window.selectedCity || '<?php echo $data["city_id"]; ?>';
                        citySelect.value = selectedCity;
                    })
                    .catch(error => {
                        console.error('Error loading cities:', error);
                    });
            }

            // Placeholder for loadAcademicDetails function
            function loadAcademicDetails() {
                // Implement your academic details loading logic here
                console.log('Loading academic details');
            }
        });

    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to get all currently selected board values
            function getCurrentSelectedBoards() {
                const boardSelects = document.querySelectorAll('.board-select');
                return Array.from(boardSelects)
                    .map(select => select.value)
                    .filter(value => value !== '');
            }

            // Function to load boards dynamically
            function loadBoards(selectElement = null) {
                // If no specific select element is provided, select all board dropdowns
                const boardSelects = selectElement
                    ? [selectElement]
                    : document.querySelectorAll('.board-select');

                // Get currently selected boards
                const currentSelectedBoards = getCurrentSelectedBoards();

                // Use fetch for modern AJAX
                fetch('get_boards.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(boards => {
                        boardSelects.forEach(select => {
                            // Remember current selection
                            const currentSelectedValue = select.value;

                            // Clear existing options
                            select.innerHTML = '';

                            // Add default option
                            const defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = '-- Select Board --';
                            select.appendChild(defaultOption);

                            // Populate board options
                            boards.forEach(board => {
                                // Only add board if:
                                // 1. It's not selected in any other dropdown, OR
                                // 2. It's the current dropdown's existing selection
                                if (!currentSelectedBoards.includes(board.id) || board.id === currentSelectedValue) {
                                    const option = document.createElement('option');
                                    option.value = board.id;
                                    option.textContent = board.board_name;

                                    // Restore previous selection if it exists
                                    if (board.id === currentSelectedValue) {
                                        option.selected = true;
                                    }

                                    select.appendChild(option);
                                }
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error loading boards:', error);
                        boardSelects.forEach(select => {
                            select.innerHTML = '<option value="">Error Loading Boards</option>';
                        });
                    });
            }

            // Initial board loading
            loadBoards();

            // Track board selections
            document.getElementById('academic-details-container').addEventListener('change', function (e) {
                if (e.target.classList.contains('board-select')) {
                    // Reload boards to update other dropdowns
                    loadBoards();
                }
            });

            // Add Academic Details Row
            document.getElementById('add-academic').addEventListener('click', function () {
                const container = document.getElementById('academic-details-container');

                // Get the current number of academic rows
                const currentRowCount = container.querySelectorAll('.academic-container').length;

                // Create new academic row
                const newRow = document.createElement('div');
                newRow.className = 'academic-container';
                newRow.innerHTML = `
                <div class="form-grid">
                <div class="form-group">
                    <label>Board:</label>
                    <select name="academic[${currentRowCount}][boardId]" class="board-select" id="board-${currentRowCount}" required>
                        <option value="">Loading...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Courses:</label>
                    <input type="text" name="academic[${currentRowCount}][courseName]" required>
                </div>

                <div class="form-group">
                    <label>Total Marks:</label>
                    <input type="number" name="academic[${currentRowCount}][totalMarks]" required>
                </div>

                <div class="form-group">
                    <label>Marks Secured:</label>
                    <input type="number" name="academic[${currentRowCount}][securedMarks]" required >
                </div>

                <div class="form-group">
                    <label>Percentage:</label>
                    <input type="text" name="academic[${currentRowCount}][percentageScore]" readonly>
                </div>

                <div class="form-group">
                    <label>Reference Files:</label>
                    <input type="file" name="referenceFiles[${currentRowCount}][]" multiple required>
                </div>
            </div>

            <button type="button" class="remove-btn" onclick="removeAcademicRow(this)">Remove</button>
        `;

                // Append new row
                container.appendChild(newRow);

                // Load boards for the new row
                const newBoardSelect = newRow.querySelector('.board-select');
                loadBoards(newBoardSelect);
            });

            // Percentage calculation
            document.getElementById('academic-details-container').addEventListener('input', function (e) {
                if (e.target.name && (e.target.name.includes('totalMarks') || e.target.name.includes('securedMarks'))) {
                    const row = e.target.closest('.academic-container');
                    const totalMarksInput = row.querySelector('input[name$="[totalMarks]"]');
                    const securedMarksInput = row.querySelector('input[name$="[securedMarks]"]');
                    const percentageInput = row.querySelector('input[name$="[percentageScore]"]');

                    const totalMarks = parseFloat(totalMarksInput.value) || 0;
                    const securedMarks = parseFloat(securedMarksInput.value) || 0;
                    if (totalMarks < securedMarks) {
                        alert('secured marks should be less than total marks');
                        securedMarksInput.value = '';
                        percentageInput.value = '';
                    }

                    if (totalMarks > 0) {
                        const percentage = ((securedMarks / totalMarks) * 100).toFixed(2);
                        percentageInput.value = percentage;
                    }
                }
            });

            // Remove academic row
            window.removeAcademicRow = function (button) {
                const rowToRemove = button.closest('.academic-container');
                const container = document.getElementById('academic-details-container');

                if (container.querySelectorAll('.academic-container').length === 1) {
                    const noDetailsMessage = document.createElement('p');
                    noDetailsMessage.textContent = 'No academic details available. Click + to add.';
                    container.innerHTML = '';
                    container.appendChild(noDetailsMessage);
                } else {
                    rowToRemove.remove();
                }

                // Reload boards to update dropdowns
                loadBoards();
            };
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            // No leading spaces validation
            $.validator.addMethod("noleadingspace", function (val, e) {
                return this.optional(e) || /^\S.*$/.test(val);
            }, "Leading spaces are not allowed");

            // Regex validation (letters, numbers, spaces, apostrophes, and dots)
            $.validator.addMethod("regex", function (val, e) {
                return this.optional(e) || /^[a-zA-Z0-9'.\s]{1,40}$/.test(val);
            }, "Only letters, numbers, spaces, apostrophes, and dots allowed (Max 40 characters)");

            // Custom validation: Prevent negative or zero marks
            $.validator.addMethod("positiveNumber", function (val, e) {
                return this.optional(e) || (val > 0);
            }, "Value must be greater than zero");

            // Custom validation: Ensure secured marks do not exceed total marks
            $.validator.addMethod("securedLessThanTotal", function (val, e) {
                const totalMarks = $(e).closest('.form-grid').find('[name^="academic["][name$="[totalMarks]"]').val();
                return this.optional(e) || (parseFloat(val) <= parseFloat(totalMarks));
            }, "Secured marks cannot exceed total marks");

            // File validation: Allow only specific formats and size limit (5MB)
            $.validator.addMethod("validFile", function (val, e) {
                if (e.files.length === 0) return true; // If no file, allow it
                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                const maxSize = 5 * 1024 * 1024; // 5MB
                return Array.from(e.files).every(file => allowedTypes.includes(file.type) && file.size <= maxSize);
            }, "Allowed file types: JPG, PNG, PDF (Max size: 5MB)");

            // Form validation rules
            $("#signupForm").validate({
                rules: {
                    name: { required: true, regex: true, noleadingspace: true },
                    contact: { required: true, number: true, minlength: 10, maxlength: 10 },
                    address: { required: true, regex: true, noleadingspace: true },
                    state: { required: true },
                    district: { required: true },
                    city: { required: true },
                    // profile_photo: { required: true },

                    "academic[0][boardId]": { required: true },
                    "academic[0][courseName]": { required: true },
                    "academic[0][totalMarks]": { required: true, number: true, positiveNumber: true },
                    "academic[0][securedMarks]": { required: true, number: true, securedLessThanTotal: true },
                    "academic[0][percentageScore]": { required: true, number: true },
                    // "referenceFiles[0][]": { required: true, validFile: true }
                },
                messages: {
                    name: { required: "Name is required" },
                    contact: { required: "Contact is required", number: "Enter a valid contact number" },
                    address: { required: "Address is required" },
                    state: { required: "State is required" },
                    district: { required: "District is required" },
                    city: { required: "City is required" },
                    // profile_photo: { required: "Profile photo is required" },

                    "academic[0][boardId]": { required: "Board is required" },
                    "academic[0][courseName]": { required: "Course name is required" },
                    "academic[0][totalMarks]": { required: "Total marks are required", number: "Enter a valid number" },
                    "academic[0][securedMarks]": { required: "Marks secured are required", number: "Enter a valid number" },
                    "academic[0][percentageScore]": { required: "Percentage is required", number: "Enter a valid number" },
                    // "referenceFiles[0][]": { required: "Reference file is required" }
                },
                errorPlacement: function (err_msg, e) {
                    err_msg.addClass("err_msg");
                    err_msg.insertAfter(e);
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });

            // Auto-calculate percentage when secured marks or total marks change
            // $(document).on('input', '[name^="academic["][name$="[totalMarks]"], [name^="academic["][name$="[securedMarks]"]', function () {
            //     const formGrid = $(this).closest('.form-grid');
            //     const totalMarks = parseFloat(formGrid.find('[name^="academic["][name$="[totalMarks]"]').val()) || 0;
            //     const securedMarks = parseFloat(formGrid.find('[name^="academic["][name$="[securedMarks]"]').val()) || 0;

            //     if (totalMarks > 0 && securedMarks >= 0) {
            //         const percentage = (securedMarks / totalMarks) * 100;
            //         formGrid.find('[name^="academic["][name$="[percentageScore]"]').val(percentage.toFixed(2));
            //     } else {
            //         formGrid.find('[name^="academic["][name$="[percentageScore]"]').val('');
            //     }
            // });

            // Add validation for dynamically added academic rows
            $('#add-academic').on('click', function () {
                const currentRowCount = $('#academic-details-container .academic-container').length;

                // Add validation rules dynamically
                $(`[name="academic[${currentRowCount}][boardId]"]`).rules("add", { required: true, messages: { required: "Board is required" } });
                $(`[name="academic[${currentRowCount}][courseName]"]`).rules("add", { required: true, messages: { required: "Course name is required" } });
                $(`[name="academic[${currentRowCount}][totalMarks]"]`).rules("add", { required: true, number: true, positiveNumber: true, messages: { required: "Total marks are required", number: "Enter a valid number" } });
                $(`[name="academic[${currentRowCount}][securedMarks]"]`).rules("add", { required: true, number: true, securedLessThanTotal: true, messages: { required: "Marks secured are required", number: "Enter a valid number" } });
                $(`[name="academic[${currentRowCount}][percentageScore]"]`).rules("add", { required: true, number: true, messages: { required: "Percentage is required", number: "Enter a valid number" } });
                $(`[name="referenceFiles[${currentRowCount}][]"]`).rules("add", { required: true, validFile: true, messages: { required: "Reference file is required" } });
            });

            // Prevent form submission if there are validation errors
            $("#signupForm").on("submit", function (e) {
                if (!$(this).valid()) {
                    e.preventDefault();
                }
            });
        });

    </script>

</body>

</html>