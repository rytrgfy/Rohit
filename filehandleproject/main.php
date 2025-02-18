<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dataPage</title>
</head>

<body>
    <h1>your details what you have entered</h1>
    <?php


// here data are been checked the data are post or get and check 
// the data then send to the function to trim and for formatting the data
    $name = $email = $gender = $comment = $website = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = properval($_POST["name"]);
        $email = properval( $_POST["email"]);
        $website = properval($_POST["website"]);
        $gender = properval($_POST["gender"]);
        $comment = properval($_POST["comment"]);
    }
// upto this 
   
    




// data is getting trim and all the unused data partical 
// like . / <> etc are been removed
//  and all the white spaces are also been removed
    function properval($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
// upto this 


    echo "your name is " . $name . "</br>";
    echo "your entered email is   " . $email . "</br>";
    echo "your website   " . $website . "</br>";
    echo "your comment   " . $comment . "</br>";
    echo "your gender   " . $gender . "</br>";










    ?>

</body>

</html>