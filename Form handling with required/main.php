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
    $nameerr = $emailerr = $websiteerr = $commenterr = $genderserr = "";
    $name = $email = $website = $comment = $gender = "";
    

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        //for name error and $name variable check 
        if (empty($_POST["name"])) {
            $nameerr = "name is required";
            exit();
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



    
    echo "Your Name: " . $name . "<br>";
    echo "Your Email: " . $email . "<br>";
    echo "Your Website: " . $website . "<br>";
    echo "Your Comment: " . $comment . "<br>";
    echo "Your Gender: " . $gender . "<br>";
    




    
    



    ?>

</body>

</html>