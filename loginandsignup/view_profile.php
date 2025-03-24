<?php
$id = $_GET['id'];
include 'dbconn.php';
session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: index.html");
//     exit();
// }

if($_SESSION['user_id'] != $id){
    header("Location: index.html");
    exit();
}

$fetch_data_sql = "SELECT signup.name, signup.contact, signup.address, signup.profile_photo, state.state_name, district.district_name, city.city_name, academic_details.board, academic_details.courses, academic_details.total_marks, academic_details.secured_marks, academic_details.percentage, academic_details.reference_file FROM signup JOIN academic_details ON signup.id = academic_details.signup_id JOIN State ON signup.State = State.id JOIN district ON signup.dist = district.id JOIN city ON signup.city = city.id WHERE signup.id = $id";
$result = $conn->query($fetch_data_sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
$data = $result->fetch_assoc();
// print_r($data);
// echo "<pre>";


// // echo $rowcount;
// print_r($rowcount);
// // exit();

$first_record = $result->fetch_assoc();
// Reset the pointer to get all academic records again
$result->data_seek(0);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --border-radius: 0.5rem;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        header {
            background-color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            color: var(--primary);
            margin: 0;
        }

        .profile-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .profile-container {
                grid-template-columns: 1fr;
            }
        }

        .profile-sidebar {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--light);
            margin-bottom: 1.5rem;
        }

        .profile-info h2 {
            text-align: center;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .profile-info p {
            text-align: center;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .profile-location {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            color: #666;
        }

        .contact-info {
            margin-top: 1rem;
            width: 100%;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
        }

        .contact-item i {
            color: var(--primary);
            width: 20px;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #eee;
        }

        .card-header i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .card-header h2 {
            margin: 0;
            color: var(--primary);
        }

        .academic-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 576px) {
            .academic-info {
                grid-template-columns: 1fr;
            }
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-item label {
            display: block;
            font-weight: 600;
            color: #555;
            margin-bottom: 0.25rem;
        }

        .info-item p {
            color: var(--dark);
            background-color: var(--light);
            padding: 0.75rem;
            border-radius: 0.25rem;
            margin: 0;
        }

        .marks-container {
            margin-top: 1.5rem;
        }

        .progress-container {
            background-color: #e9ecef;
            border-radius: 0.5rem;
            height: 1.5rem;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            transition: width 0.5s ease;
        }

        .marks-details {
            display: flex;
            justify-content: space-between;
        }

        .document-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .document-link:hover {
            text-decoration: underline;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            /* Space between links */
            background-color: #2c3e50;
            /* Dark navy background */
            padding: 10px 20px;
            border-radius: 8px;
            /* Rounded corners */
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px 15px;
            transition: background 0.3s, color 0.3s;
            border-radius: 5px;
        }

        .nav-links a:hover {
            background-color: #3498db;
            /* Light blue hover effect */
            color: #fff;
        }
    </style>
</head>

<body>

    <body>
        <div class="container">
            <header>
                <h1>Student Profile</h1> 
                <div class="nav-links">
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>
                </div>
                <div>
                    <a href="edit_profile.php?id=<?php echo $id; ?>" style="text-decoration: none;">
                        <button
                            style="background-color: var(--primary); color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer;">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                </div>
            </header>

            <div class="profile-container">
                <div class="profile-sidebar">
                    <img src="photos/<?php echo $first_record['profile_photo']; ?>" alt="Profile Photo"
                        class="profile-image">
                    <div class="profile-info">
                        <h2><?php echo $first_record['name']; ?></h2>
                        <div class="profile-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $first_record['city_name']; ?>,
                                <?php echo $first_record['state_name']; ?></span>
                        </div>
                    </div>

                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span><?php echo $first_record['contact']; ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marked-alt"></i>
                            <span><?php echo $first_record['address']; ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-city"></i>
                            <span><?php echo $first_record['district_name']; ?> /
                                <?php echo $first_record['city_name']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="main-content">
                    <?php
                    // Loop through all academic records
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-graduation-cap"></i>
                                <h2>Academic Details</h2>
                            </div>

                            <div class="academic-info">
                                <div class="info-item">
                                    <label>Board</label>
                                    <p><?php echo $row['board']; ?></p>
                                </div>

                                <div class="info-item">
                                    <label>Course</label>
                                    <p><?php echo $row['courses']; ?></p>
                                </div>
                            </div>

                            <div class="marks-container">
                                <label>Performance</label>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?php echo $row['percentage']; ?>%">
                                        <?php echo $row['percentage']; ?>%
                                    </div>
                                </div>

                                <div class="marks-details">
                                    <span>Secured: <strong><?php echo $row['secured_marks']; ?></strong></span>
                                    <span>Total: <strong><?php echo $row['total_marks']; ?></strong></span>
                                </div>

                                <?php if ($row['reference_file']): ?>
                                    <a href="file_uploads_data/<?php echo $row['reference_file']; ?>" class="document-link"
                                        target="_blank">
                                        <i class="fas fa-file-alt"></i> View Academic Documents
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <script>

            document.addEventListener('DOMContentLoaded', function () {
                const securedMarks = <?php echo $data['secured_marks']; ?>;
                const totalMarks = <?php echo $data['total_marks']; ?>;
                const percentage = (securedMarks / totalMarks) * 100;

                document.querySelector('.progress-bar').style.width = percentage + '%';
                document.querySelector('.progress-bar').textContent = percentage.toFixed(2) + '%';
            });
        </script>
    </body>

</html>