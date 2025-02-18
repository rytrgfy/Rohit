<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>


    <?php
    // header('Content-Type: application/json');
    // $nameerr = $emailerr = $websiteerr = $commenterr = $gendererr = "";
    // $name = $email = $website = $comment = $gender = "";
    

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        header('Content-Type: application/json'); // Ensure response is JSON
    
        $nameerr = $emailerr = $websiteerr = $commenterr = $gendererr = "";
        $name = $email = $website = $comment = $gender = "";

        //for name error and $name variable check 
        if (empty($_POST["name"])) {
            $nameerr = "name is required";
        } else {
            $name = $_POST["name"];
        }
        //for email error and $email variable check 
        if (empty($_POST["email"])) {
            $emailerr = "email is required";
        } else {
            $email = $_POST["email"];
        }
        //for website  error and $website variable check
        if (empty($_POST["website"])) {
            $websiteerr = "website is required";
        } else {
            $website = $_POST["website"];
        }
        //for comments  error and $comments variable check
        if (empty($_POST["comment"])) {
            $commenterr = "comments is required";
        } else {
            $comment = $_POST["comment"];
        }
        //for gender error and $gender variable check 
        if (empty($_POST["gender"])) {
            $gendererr = "gender is required";
        } else {
            $gender = $_POST["gender"];
        }
    }



    // Display the sanitized input data
    // echo "Your Name: " . $name . "<br>";
    // echo "Your Email: " . $email . "<br>";
    // echo "Your Website: " . $website . "<br>";
    // echo "Your Comment: " . $comment . "<br>";
    // echo "Your Gender: " . $gender . "<br>";
    



    $response = [
        "nameERR" => $nameerr,
        "EmailERR" => $emailerr,
        "WebsiteERR" => $websiteerr,
        "CommentERR" => $commenterr,
        "GenderERR" => $gendererr
    ];

    echo json_encode($response);
    // exit();

    
    



    ?>

</body>

</html>