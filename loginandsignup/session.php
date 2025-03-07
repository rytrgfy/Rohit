<?php
// this page refer for testing sessions on the page
session_start();
$_SESSION['test'] = "Session is working!";
echo $_SESSION['test'];
echo $_SESSION['user_id'];
