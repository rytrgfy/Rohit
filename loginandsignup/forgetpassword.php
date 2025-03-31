<?php

include "dbconn.php";
session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    die("Unauthorized access. Please log in.");
}
if($_SESSION['username'] == 'Admin'){
    header("Location: admin.php");
    exit();
}

$username = $_SESSION['username'];
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $oldpassword = md5($_POST['oldpassword']); // Hash old password
    $newpassword = $_POST['newpassword'];
    $cnfpassword = $_POST['cnfpassword'];

    // Get user's current password
    $sql = "SELECT password FROM signup WHERE username = '$username'";
    $result = $conn->query($sql);

    if (!$result || $result->num_rows === 0) {
        $error = 'Failed to fetch user data.';
    } else {
        $data = $result->fetch_assoc();
        $db_password = $data['password']; // Current password from database (MD5)

        // Validate passwords
        if ($oldpassword !== $db_password) {
            $error = 'Old password is incorrect!';
        } elseif ($oldpassword == md5($newpassword)) { // Ensure new password is different
            $error = 'Old password cannot be the same as the new password!';
        } elseif ($newpassword !== $cnfpassword) {
            $error = 'New password and confirm password do not match!';
        } else {
            // Hash new password with MD5
            $hashedNewPassword = md5($newpassword);

            // Update password
            $sql_update = "UPDATE signup SET password = '$hashedNewPassword' WHERE username = '$username'";
            if ($conn->query($sql_update)) {
                echo "<script>alert('Password updated successfully!'); window.location.href='logout.php';</script>";
                exit();
            } else {
                $error = 'Failed to update password.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }
        
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="password"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }
        
        input[type="password"].error-input {
            border-color: #ff0000;
        }
        
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        input[type="submit"]:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        
        .error {
            color: #ff0000;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
        
        .success {
            color: #4CAF50;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .password-strength {
            height: 5px;
            width: 100%;
            margin-top: 5px;
            background-color: #ddd;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }
        
        .password-strength-text {
            font-size: 12px;
            margin-top: 3px;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 38px;
            cursor: pointer;
            color: #555;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        
        <?php if (!empty($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form id="passwordForm" method="POST" action="">
            <div class="form-group">
                <label for="oldpassword">Old Password</label>
                <input type="password" id="oldpassword" name="oldpassword">
                <span class="toggle-password" data-target="oldpassword">Show</span>
                <span class="error" id="oldpassword-error"></span>
            </div>
            
            <div class="form-group">
                <label for="newpassword">New Password</label>
                <input type="password" id="newpassword" name="newpassword">
                <span class="toggle-password" data-target="newpassword">Show</span>
                <span class="error" id="newpassword-error"></span>
                <div class="password-strength">
                    <div class="password-strength-bar" id="password-strength-bar"></div>
                </div>
                <div class="password-strength-text" id="password-strength-text"></div>
            </div>
            
            <div class="form-group">
                <label for="cnfpassword">Confirm New Password</label>
                <input type="password" id="cnfpassword" name="cnfpassword">
                <span class="toggle-password" data-target="cnfpassword">Show</span>
                <span class="error" id="cnfpassword-error"></span>
            </div>
            
            <input type="submit" id="submit-btn" value="Change Password">
        </form>
    </div>

    <script>
        $(document).ready(function() {
            const form = $('#passwordForm');
            const submitBtn = $('#submit-btn');
            let formValid = false;
            
            // Function to check form validity
            function validateForm() {
                const oldPassword = $('#oldpassword').val();
                const newPassword = $('#newpassword').val();
                const cnfPassword = $('#cnfpassword').val();
                let isValid = true;
                
                // Reset errors
                $('.error').text('');
                $('input').removeClass('error-input');
                
                // Validate old password
                if (oldPassword === '') {
                    $('#oldpassword-error').text('Please enter your old password');
                    $('#oldpassword').addClass('error-input');
                    isValid = false;
                }
                
                // Validate new password
                if (newPassword === '') {
                    $('#newpassword-error').text('Please enter a new password');
                    $('#newpassword').addClass('error-input');
                    isValid = false;
                } else if (newPassword.length < 8) {
                    $('#newpassword-error').text('Password must be at least 8 characters');
                    $('#newpassword').addClass('error-input');
                    isValid = false;
                } else if (!/[A-Z]/.test(newPassword)) {
                    $('#newpassword-error').text('Password must contain at least one uppercase letter');
                    $('#newpassword').addClass('error-input');
                    isValid = false;
                } else if (!/[a-z]/.test(newPassword)) {
                    $('#newpassword-error').text('Password must contain at least one lowercase letter');
                    $('#newpassword').addClass('error-input');
                    isValid = false;
                } else if (!/[0-9]/.test(newPassword)) {
                    $('#newpassword-error').text('Password must contain at least one number');
                    $('#newpassword').addClass('error-input');
                    isValid = false;
                } else if (!/[^A-Za-z0-9]/.test(newPassword)) {
                    $('#newpassword-error').text('Password must contain at least one special character');
                    $('#newpassword').addClass('error-input');
                    isValid = false;
                }
                
                // Check if old and new passwords are the same
                if (oldPassword === newPassword && oldPassword !== '') {
                    $('#newpassword-error').text('New password cannot be the same as old password');
                    $('#newpassword').addClass('error-input');
                    isValid = false;
                }
                
                // Validate confirm password
                if (cnfPassword === '') {
                    $('#cnfpassword-error').text('Please confirm your new password');
                    $('#cnfpassword').addClass('error-input');
                    isValid = false;
                } else if (cnfPassword !== newPassword) {
                    $('#cnfpassword-error').text('Passwords do not match');
                    $('#cnfpassword').addClass('error-input');
                    isValid = false;
                }
                
                return isValid;
            }
            
            // Password strength meter
            function checkPasswordStrength(password) {
                let strength = 0;
                const strengthBar = $('#password-strength-bar');
                const strengthText = $('#password-strength-text');
                
                if (password.length === 0) {
                    strengthBar.css({
                        'width': '0%',
                        'background-color': '#DDD'
                    });
                    strengthText.text('');
                    return;
                }
                
                // Add points for length
                if (password.length >= 8) strength += 1;
                if (password.length >= 12) strength += 1;
                
                // Add points for complexity
                if (/[A-Z]/.test(password)) strength += 1;
                if (/[a-z]/.test(password)) strength += 1;
                if (/[0-9]/.test(password)) strength += 1;
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                
                // Calculate percentage
                const strengthPercent = (strength / 6) * 100;
                
                // Update visuals
                let color, text;
                if (strengthPercent < 33) {
                    color = '#FF3A19';
                    text = 'Weak';
                } else if (strengthPercent < 66) {
                    color = '#FFA319';
                    text = 'Medium';
                } else {
                    color = '#4CAF50';
                    text = 'Strong';
                }
                
                strengthBar.css({
                    'width': strengthPercent + '%',
                    'background-color': color
                });
                strengthText.text(text);
            }
            
            // Show/hide password
            $('.toggle-password').click(function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    $(this).text('Hide');
                } else {
                    input.attr('type', 'password');
                    $(this).text('Show');
                }
            });
            
            // Real-time validation
            $('#passwordForm input').on('input', function() {
                if ($(this).attr('id') === 'newpassword') {
                    checkPasswordStrength($(this).val());
                }
                
                formValid = validateForm();
                submitBtn.prop('disabled', !formValid);
            });
            
            // Form submission
            form.on('submit', function(e) {
                formValid = validateForm();
                
                if (!formValid) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Initial validation
            submitBtn.prop('disabled', true);
        });
    </script>
</body>
</html>