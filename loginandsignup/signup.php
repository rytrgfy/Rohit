<?php
include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $contact = $_POST["contact"];
    $address = $_POST["address"];

    $profile_photo = '';

    // Profile photo upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $file_name = $_FILES['profile_photo']['name'];
        $tmp_name = $_FILES['profile_photo']['tmp_name'];
        $target_path = "photos/" . $file_name;

        if (move_uploaded_file($tmp_name, $target_path)) {
            $profile_photo = $file_name;
        } else {
            echo "Photo upload failed!";
            exit();
        }
    } else {
        echo "No photo selected.";
        exit();
    }

    // Login credentials
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cnfpassword = $_POST["cnfpassword"];

    // Password validation
    if ($password !== $cnfpassword) {
        echo "Passwords do not match!";
        exit();
    }

    // Insert into signup table
    $insert_sql = "INSERT INTO signup (name, contact, address, profile_photo, username, password) 
                   VALUES ('$name', '$contact', '$address', '$profile_photo', '$username', '$password')";

    if ($conn->query($insert_sql) === TRUE) {
        $last_insert_id = $conn->insert_id;

        // Process academic details dynamically
        if (isset($_POST['academic']) && is_array($_POST['academic'])) {
            foreach ($_POST['academic'] as $key => $academic) {
                $board = $academic["board"];
                $courses = $academic["courses"];
                $total_marks = $academic["total_marks"];
                $secured_marks = $academic["secured_marks"];
                $percentage = $academic["percentage"];

                $reference_files = [];
                // Reference file upload for each academic detail
                if (!empty($_FILES['reference_files']['name'][$key][0])) {
                    foreach ($_FILES['reference_files']['name'][$key] as $file_key => $file_name) {
                        $tmp_name = $_FILES['reference_files']['tmp_name'][$key][$file_key];
                        $target_path = "file_uploads_data/" . $file_name;

                        if (move_uploaded_file($tmp_name, $target_path)) {
                            $reference_files[] = $file_name;
                        } else {
                            echo "File upload failed!";
                            exit();
                        }
                    }
                }

                // Concatenate reference files into a single string
                $reference_files_str = implode(',', $reference_files);

                $insert_academic_details = "INSERT INTO academic_details (signup_id, board, courses, total_marks, secured_marks, percentage, reference_file) 
                                            VALUES ('$last_insert_id', '$board', '$courses', '$total_marks', '$secured_marks', '$percentage', '$reference_files_str')";

                if (!$conn->query($insert_academic_details)) {
                    echo "Error inserting academic details: " . $conn->error;
                    exit();
                }
            }
        }

        echo "<script>alert('Data submitted successfully');window.location.href='index.html';</script>";
    } else {
        echo $conn->error;
    }
}
?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="main">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="basicinfo">
                <h3>Basic Info</h3>
                <div class="basicinfodivs">
                    <div class="form-group name">
                        <label>Name:</label>
                        <input type="text" name="name" placeholder="Enter your name">
                    </div>

                    <div class="form-group contact ">
                        <label>Contact:</label>
                        <input type="tel" name="contact" placeholder="Enter your mobile number">
                    </div>

                    <div class="form-group address ">
                        <label>Address:</label>
                        <textarea name="address" id="address" cols="9" rows="3"></textarea>
                    </div>

                    <div class="form-group state ">
                        <label>Select State:</label>
                        <select name="state" id="state">
                            <option>-- Select State --</option>
                        </select>
                    </div>

                    <div class="form-group district ">
                        <label>Select District:</label>
                        <select name="district" id="district">
                            <option>-- Select District --</option>
                        </select>
                    </div>

                    <div class="form-group city ">
                        <label>Select City:</label>
                        <select name="city" id="city">
                            <option>-- Select City --</option>
                        </select>
                    </div>

                    <div class="form-group profilephoto ">
                        <label>Profile Photo:</label>
                        <input type="file" name="profile_photo" accept="image/*">
                    </div>
                </div>
            </div>

            <h3>Academic Details</h3>
            <div id="academic-container">
                <div class="academic-section">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Board:</label>
                            <select name="academic[0][board]" class="board-select">
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Courses:</label>
                            <input type="text" name="academic[0][courses]" class="courses-input"
                                placeholder="Enter your courses">
                        </div>

                        <div class="form-group">
                            <label>Total Marks:</label>
                            <input type="number" name="academic[0][total_marks]" class="total-marks"
                                placeholder="Enter total marks">
                        </div>

                        <div class="form-group">
                            <label>Marks Secured:</label>
                            <input type="number" name="academic[0][secured_marks]" class="secured-marks"
                                placeholder="Enter marks secured">
                        </div>

                        <div class="form-group">
                            <label>Percentage:</label>
                            <input type="text" name="academic[0][percentage]" class="percentage-field" readonly>
                        </div>

                        <div class="form-group">
                            <label>Reference Files:</label>
                            <input type="file" name="reference_files[0][]" multiple>
                        </div>
                    </div>

                    <button type="button" class="remove-btn" style="display:none;">Remove</button>
                </div>
            </div>

            <button type="button" id="add-academic">Add Another Academic Detail</button>

            <h3>Login Details</h3>
            <div class="logindetails">
                <div class="form-group username logindetails">
                    <label>Username:</label>
                    <input type="text" name="username" placeholder="Enter your username">
                </div>

                <div class="form-group password logindetails">
                    <label>Password:</label>
                    <input type="password" name="password" placeholder="Enter your password">
                </div>

                <div class="form-group cnfpassword logindetails">
                    <label>Confirm Password:</label>
                    <input type="password" name="cnfpassword" placeholder="Confirm your password">
                </div>
            </div>

            <div class="submit-container">
                <input type="submit" name="signup" value="Sign Up">
            </div>
        </form>
    </div>

    <!-- ajax for dynamic add -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Load states on page load
            loadStates();

            $('#state').on('change', function () {
                var stateId = $(this).val();
                loadDistricts(stateId);
            });

            $('#district').on('change', function () {
                var districtId = $(this).val();
                loadCities(districtId);
            });

            function loadStates() {
                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: { type: 'state' },
                    success: function (response) {
                        $('#state').html(response);
                    }
                });
            }

            function loadDistricts(stateId) {
                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: { type: 'district', id: stateId },
                    success: function (response) {
                        $('#district').html(response);
                        $('#city').html('<option value="">Select City</option>'); // Clear city dropdown
                    }
                });
            }

            function loadCities(districtId) {
                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: { type: 'city', id: districtId },
                    success: function (response) {
                        $('#city').html(response);
                    }
                });
            }

            let sectionCount = $('.academic-section').length; // Start counting from existing sections
            let selectedBoards = [];

            // Load boards from database via AJAX
            function loadBoards(selectElement, excludeValues = []) {
                const currentValue = $(selectElement).val(); // Get current selection

                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: {
                        type: 'boards',
                        selected: excludeValues,
                        current_board: currentValue
                    },
                    success: function (response) {
                        $(selectElement).html(response);
                        $(selectElement).val(currentValue); // Retain previous selection
                    }
                });
            }

            // Calculate percentage
            function calculatePercentage(section) {
                const totalMarks = parseFloat($(section).find('.total-marks').val()) || 0;
                const securedMarks = parseFloat($(section).find('.secured-marks').val()) || 0;

                if (totalMarks > 0) {
                    const percentage = (securedMarks / totalMarks * 100).toFixed(2);
                    $(section).find('.percentage-field').val(percentage + '%');
                } else {
                    $(section).find('.percentage-field').val('');
                }
            }

            // Update board selections in all dropdowns
            function updateBoardSelections() {
                selectedBoards = [];

                // Collect all selected board values
                $('.board-select').each(function () {
                    const value = $(this).val();
                    if (value && value !== '') {
                        selectedBoards.push(value);
                    }
                });

                // Update all board dropdowns with available options
                $('.board-select').each(function () {
                    const currentValue = $(this).val();
                    loadBoards(this, selectedBoards.filter(v => v !== currentValue)); // Exclude current selection
                });
            }

            // Listen for board selection changes
            $(document).on('change', '.board-select', function () {
                updateBoardSelections();
            });

            // Calculate percentage on input change
            $(document).on('input', '.total-marks, .secured-marks', function () {
                calculatePercentage($(this).closest('.academic-section'));
            });

            // Add new academic section
            $('#add-academic').click(function () {
                sectionCount++;

                // Clone the first academic section
                const newSection = $('.academic-section:first').clone();

                // Clear input values
                newSection.find('input[type="text"], input[type="number"]').val('');

                // Update name attributes with new index
                newSection.find('[name]').each(function () {
                    const oldName = $(this).attr('name');
                    const newName = oldName.replace(/\[\d+\]/, `[${sectionCount}]`);
                    $(this).attr('name', newName);
                });

                // Reset dropdown selection
                newSection.find('.board-select').val('');

                // Reset file input
                newSection.find('input[type="file"]').val('');

                // Show remove button
                newSection.find('.remove-btn').show();

                // Append new section
                $('#academic-container').append(newSection);

                // Update board selections
                updateBoardSelections();
            });

            // Remove academic section
            $(document).on('click', '.remove-btn', function () {
                $(this).closest('.academic-section').remove();

                // Update board selections after removal
                updateBoardSelections();

                // Hide remove button if only one section remains
                if ($('.academic-section').length === 1) {
                    $('.remove-btn').hide();
                }
            });

            // Initialize first board select
            loadBoards('.board-select');
        });
    </script>
</body>

</html>