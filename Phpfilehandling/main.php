<?php

// echo $_POST["FirstName"] . "<br/>";

if (true) {

    echo"your first name is ". $_POST["FirstName"] ."<br/>";
    echo"your Last name is ". $_POST["LastName"] ."<br/>";
    echo"your gender is " . $_POST["Gender"] . "<br/>";
    // echo"your skills are " . $_POST["skills"] . "<br/>";

    if (is_array($_POST["skills"])) {
        echo "Your skills are: <br/>";
        foreach ($_POST["skills"] as $skill) {
            echo $skill . "<br/>";
        }
    } else {
        echo "Your skills are: " . $_POST["skills"] . "<br/>";
    }



}

?>