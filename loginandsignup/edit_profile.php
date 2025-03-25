<?php
include 'dbconn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$id = $_GET['id'];
echo $id;

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
WHERE signup.id = $id;
";
$result = $conn->query($fetch_data_sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
$data = $result->fetch_assoc();

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
echo $data['board_name'];
// echo $data['courses'];
// echo $data['total_marks'];
// echo $data['secured_marks'];
// echo $data['percentage'];
// echo $data['reference_file'];











?>


<!-- update html -->




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
                <input type="submit" name="signup" value="Sign Up">
            </div>
        </form>
    </div>

    <!-- Include jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            loadStates(); // Load states on page load
            loadAcademicDetails(); // Load academic details

            // Fetch districts when state changes
            $('#stateId').on('change', function () {
                let stateId = $(this).val();
                if (stateId) {
                    loadDistricts(stateId);
                }
            });

            // Fetch cities when district changes
            $('#districtId').on('change', function () {
                let districtId = $(this).val();
                if (districtId) {
                    loadCities(districtId);
                }
            });

            // Function to load states
            function loadStates() {
                $.ajax({
                    url: 'editajax.php',
                    type: 'POST',
                    data: { type: 'state' },
                    success: function (response) {
                        $('#stateId').html(response);
                        let selectedState = "<?php echo $data['state_id']; ?>";
                        $('#stateId').val(selectedState);
                        loadDistricts(selectedState);
                    }
                });
            }

            // Function to load districts
            function loadDistricts(stateId) {
                if (!stateId) return;
                $.ajax({
                    url: 'editajax.php',
                    type: 'POST',
                    data: { type: 'district', stateId: stateId },
                    success: function (response) {
                        $('#districtId').html(response);
                        let selectedDistrict = "<?php echo $data['district_id']; ?>";
                        $('#districtId').val(selectedDistrict);
                        loadCities(selectedDistrict);
                    }
                });
            }

            // Function to load cities
            function loadCities(districtId) {
                if (!districtId) return;
                $.ajax({
                    url: 'editajax.php',
                    type: 'POST',
                    data: { type: 'city', districtId: districtId },
                    success: function (response) {
                        $('#cityId').html(response);
                        let selectedCity = "<?php echo $data['city_id']; ?>";
                        $('#cityId').val(selectedCity);
                    }
                });
            }

            // Load academic details dynamically
            function loadAcademicDetails() {
                $.ajax({
                    url: 'editajax.php',
                    type: 'POST',
                    data: { type: 'academic' },
                    dataType: 'json',
                    success: function (response) {
                        $('#academic-container').html(""); // Clear existing
                        response.forEach((data, index) => {
                            addAcademicRow(index, data);
                        });
                    }
                });
            }

            // Function to add an academic row dynamically
            function addAcademicRow(index, data = {}) {
                let row = `
        <div class="academic-section">
            <div class="form-grid">
                <div class="form-group">
                    <label>Board:</label>
                    <select name="academic[${index}][board]" class="board-select" id="board-${index}">
                        <option value="">Loading...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Courses:</label>
                    <input type="text" name="academic[${index}][courses]" class="courses-input"
                        value="${data.courses || ''}" placeholder="Enter your courses">
                </div>

                <div class="form-group">
                    <label>Total Marks:</label>
                    <input type="number" name="academic[${index}][total_marks]" class="total-marks"
                        value="${data.total_marks || ''}" placeholder="Enter total marks">
                </div>

                <div class="form-group">
                    <label>Marks Secured:</label>
                    <input type="number" name="academic[${index}][secured_marks]" class="secured-marks"
                        value="${data.secured_marks || ''}" placeholder="Enter marks secured">
                </div>

                <div class="form-group">
                    <label>Percentage:</label>
                    <input type="text" name="academic[${index}][percentage]" class="percentage-field" readonly
                        value="${data.percentage || ''}">
                </div>

                <div class="form-group">
                    <label>Reference Files:</label>
                    <input type="file" name="reference_files[${index}][]" multiple>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeAcademicRow(this)">Remove</button>
        </div>
        `;
                $('#academic-container').append(row);
                loadBoards(index, data.board);
            }

            // Load board options
            function loadBoards(index, selectedBoard) {
                $.ajax({
                    url: 'editajax.php',
                    type: 'POST',
                    data: { type: 'board' },
                    success: function (response) {
                        let boardDropdown = $(`#board-${index}`);
                        boardDropdown.html(response);
                        if (selectedBoard) {
                            boardDropdown.val(selectedBoard);
                        }
                    }
                });
            }

            // Remove academic section
            window.removeAcademicRow = function (element) {
                $(element).closest('.academic-section').remove();
            };

            // Add new academic section
            $('#add-academic').on('click', function () {
                let newIndex = $('.academic-section').length;
                addAcademicRow(newIndex);
            });

        });

    </script>



</body>

</html>