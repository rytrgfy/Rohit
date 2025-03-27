<?php
include "dbconn.php";



// $state_id = $_POST['stateId'];
// echo $state_id;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $contact = $_POST["contact"];
    $address = $_POST["address"];
    $state_id = isset($_POST["state_id"]) ? $_POST["state_id"] : 0;
    $district_id = isset($_POST["district_id"]) ? $_POST["district_id"] : 0;
    $city_id = isset($_POST["city_id"]) ? $_POST["city_id"] : 0;

    // echo "State ID: " . $state_id . "<br>";
    // echo "District ID: " . $district_id . "<br>";
    // echo "City ID: " . $city_id . "<br>";
    // exit();





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
    $insert_sql = "INSERT INTO signup (name, contact, address, profile_photo, state, dist, city, username, password) 
               VALUES ('$name', '$contact', '$address', '$profile_photo', '$state_id', '$district_id', '$city_id', '$username', '$password')";


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

        // echo "<script>alert('Data submitted successfully');window.location.href='index.html';</script>";
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
                        <select name="state" id="stateId">
                            <option>-- Select State --</option>
                        </select>
                    </div>

                    <div class="form-group district ">
                        <label>Select District:</label>
                        <select name="district" id="district_id">
                            <option>-- Select District --</option>
                        </select>
                    </div>

                    <div class="form-group city ">
                        <label>Select City:</label>
                        <select name="city" id="city_id">
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
            <button type="button" id="add-academic">+</button>
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

            $('#stateId').on('change', function () {
                var stateId = $(this).val();
                loadDistricts(stateId);
                console.log("State changed:", stateId);
            });

            $('#district_id').on('change', function () {
                var districtId = $(this).val();
                loadCities(districtId);
                console.log("District changed:", districtId);
            });

            $('#city_id').on('change', function () {
                var cityId = $(this).val();
                console.log("City changed:", cityId);
            });

            function loadStates() {
                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: { type: 'state' },
                    success: function (response) {
                        $('#stateId').html(response);
                    }
                });
            }

            function loadDistricts(stateId) {
                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: { type: 'district', id: stateId },
                    success: function (response) {
                        $('#district_id').html(response);
                        $('#city_id').html('<option>-- Select City --</option>');
                    }
                });
            }

            function loadCities(districtId) {
                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: { type: 'city', id: districtId },
                    success: function (response) {
                        $('#city_id').html(response);
                    }
                });
            }

            // Fix: Form submission
            $('#signupForm').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                let formData = new FormData(this);

                // Append state, district, and city IDs
                formData.append("state_id", $("#stateId").val());
                formData.append("district_id", $("#district_id").val());
                formData.append("city_id", $("#city_id").val());

                $.ajax({
                    url: "signup.php", // Use a separate processing file
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log("Response from PHP:", response);
                        if (response.includes("successfully")) {
                            alert("form submit successfully"); // Display response to user
                            window.location.href = 'index.html'; // Redirect on success
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert("Error submitting form: " + error);
                    }
                });
            });

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
                if (securedMarks > totalMarks) {
                    alert('Marks secured cannot be greater than total marks!');
                    $(section).find('.secured-marks').val('');
                    return;
                }

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




<!------------------------------------------------------------------------------------------ 
this is done on friday state dist and city done 
data sending to db is still pending and validation of signup page is still pending 
to be done on monday 
date 21-05-2025 
 ---------------------------------------------------------------------------------------------->