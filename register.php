<?php

/*************************************************************************
* 
* Class: CS 340
*
* Notes: Inserts 'username' and 'password' from the 'Sign up' form into
* the users table.
**************************************************************************/

session_start();
ini_set('display_errors', 'On');
echo '<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title>Recipe Database</title>
    <meta name="description" content="Recipe Database">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="dashboard.css" rel="stylesheet">
    </head>
    <body>';


$db_username = 'xxxxxxxxxxx-db'; //onid username
$db_password = 'xxxxxxxxxxxxxx'; //db password
$db_name = 'xxxxxxxxx-db';       //db username (same as onid)
$db_host = 'oniddb.cws.oregonstate.edu';

$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);
if ($mysqli->connect_errno){
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

/*****************************************************
*
* Checks to see if the username is already taken and
* reports the error if it is.  Otherwise, adds username
* and password to the 'users' table.
*
******************************************************/
if(isset($_POST['username'])){
    $name = mysqli_real_escape_string($mysqli, $_POST['username']);

    $get_user = "SELECT * FROM users WHERE username='$name'";
    $run_user = mysqli_query($mysqli, $get_user);
    $check = mysqli_num_rows($run_user);
    if ($check == 1){
        echo "<script>alert('Username is already taken')</script>";
        echo "<script>window.location.href = 'home.html'</script>";
    }
    else{
        $_SESSION['username'] = $name;
        if(isset($_POST['username']) && isset($_POST['password'] )){
            $stmt = $mysqli->prepare("INSERT INTO users(username,password) VALUES (?,?)");
            $stmt->bind_param('ss', $_POST['username'], $_POST['password']);
            $stmt->execute();
            $stmt->close();
        }
    }
}



echo "<script>window.location.href = 'recipes.php'</script>";


echo "</body></html>";

?>