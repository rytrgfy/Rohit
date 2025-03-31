<?php
// this page refer for testing sessions on the page
session_start();
$_SESSION['test'] = "Session is working!";
echo $_SESSION['test'];
echo $_SESSION['user_id'];


//test





//include 'connection.php';
$id = "";
$student = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "SELECT s.*, a.board, a.course, a.percentage, a.totalmark, a.actualmark, a.attachment, 
                   st.sid, st.sname, dt.did, dt.dname, ct.cid, ct.cname 
            FROM student s 
            LEFT JOIN academic a ON s.id = a.id 
            LEFT JOIN state st ON s.state = st.sid 
            LEFT JOIN district dt ON s.district = dt.did 
            LEFT JOIN city ct ON s.city = ct.cid 
            WHERE s.id = '$id'";

    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $student = $row;
    }

    $sqlAcademic = "SELECT * FROM academic WHERE id = '$id'";
    $resultAcademic = mysqli_query($conn, $sqlAcademic);

    while ($rowAcademic = mysqli_fetch_assoc($resultAcademic)) {
        $academicRecords[] = $rowAcademic;
    }
}


if (isset($_POST['submit'])) {

    // Collecting student data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $upload_dir = __DIR__ . "/images/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image = "";
    if (!empty($_FILES['image']['name'])) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (in_array($image_ext, $allowed_types)) {
            $image = time() . "_" . $image_name;
            move_uploaded_file($image_tmp, "{$upload_dir}{$image}");
        }
    }

    if (!empty($id)) {
        // Step 1: Update student record
        $sql1 = "UPDATE student SET 
            name='$name', email='$email', contact='$contact', gender='$gender', 
            image='$image', state='$state', district='$district', city='$city', 
            pincode='$pincode', address='$address', username='$username', 
            password='$password' WHERE id='$id'";

        if (mysqli_query($conn, $sql1)) {
            // Step 2: Delete the existing academic records for this student
            $sqlDeleteAcademic = "DELETE FROM academic WHERE id='$id'";
            mysqli_query($conn, $sqlDeleteAcademic);

            // Step 3: Insert the new academic records
            foreach ($_POST['board'] as $key => $board) {
                $course = $_POST['course'][$key];
                $percentage = $_POST['percentage'][$key];
                $totalmark = $_POST['totalmark'][$key];
                $actualmark = $_POST['actualmark'][$key];

                $attachment = "";
                if (!empty($_FILES['attachment']['name'][$key])) {
                    $attachment_name = time() . "_" . $_FILES['attachment']['name'][$key];
                    move_uploaded_file($_FILES['attachment']['tmp_name'][$key], "{$upload_dir}{$attachment_name}");
                    $attachment = $attachment_name;
                }

                // Rashmita Rout Bbsr Surya, [31-03-2025 11:56 AM]
                $sql2 = "INSERT INTO academic (board, course, percentage, totalmark, actualmark, attachment, id) 
                         VALUES ('$board', '$course', '$percentage', '$totalmark', '$actualmark', '$attachment', '$id')";
                mysqli_query($conn, $sql2);
            }

            // Redirect to the form with a success message
            header("Location: form.php?id=$id&update=success");
            exit();
        }
    } else {
        // If the ID is not present, we insert a new student record
        $sql1 = "INSERT INTO student (name, email, contact, gender, image, state, district, city, pincode, address, username, password) 
                 VALUES ('$name', '$email', '$contact', '$gender', '$image', '$state', '$district', '$city', '$pincode', '$address', '$username', '$password')";

        if (mysqli_query($conn, $sql1)) {
            $student_id = mysqli_insert_id($conn);

            // Inserting academic records for the new student
            if (!empty($_POST['board']) && is_array($_POST['board'])) {
                foreach ($_POST['board'] as $key => $board) {
                    $course = $_POST['course'][$key];
                    $percentage = $_POST['percentage'][$key];
                    $totalmark = $_POST['totalmark'][$key];
                    $actualmark = $_POST['actualmark'][$key];

                    $attachment = "";
                    if (!empty($_FILES['attachment']['name'][$key])) {
                        $attachment_name = time() . "_" . $_FILES['attachment']['name'][$key];
                        move_uploaded_file($_FILES['attachment']['tmp_name'][$key], "{$upload_dir}{$attachment_name}");
                        $attachment = $attachment_name;
                    }

                    $sql2 = "INSERT INTO academic (board, course, percentage, totalmark, actualmark, attachment, id) 
                             VALUES ('$board', '$course', '$percentage', '$totalmark', '$actualmark', '$attachment', '$student_id')";

                    mysqli_query($conn, $sql2);
                }
            }
            header("Location: form.php");
            exit();
        }
    }
}
?>