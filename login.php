<?php

/*************************************************************************
* Class: CS 340
*
* Notes: Verifies that both the username and password entered into the
* 'sign in' form are already in the username database.
**************************************************************************/

session_start();
ini_set('display_errors', 'On');

$db_username = 'xxxxxxxxxxx-db'; //onid username
$db_password = 'xxxxxxxxxxxxxx'; //db password
$db_name = 'xxxxxxxxx-db';       //db username (same as onid)
$db_host = 'oniddb.cws.oregonstate.edu';

$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);
if ($mysqli->connect_errno){
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}


/*******************************************
*
*	Checks to confirm that the username 
*	and password are valid.  Proceeds to
*	recipes.php if vald and gives an error
*	if invalid and goes back to home.html
*
********************************************/
if(isset($_POST['u_name'])){
	$name = mysqli_real_escape_string($mysqli, $_POST['u_name']);
	$pass = mysqli_real_escape_string($mysqli, $_POST['u_pass']);

	$get_user = "SELECT * FROM users WHERE username='$name' AND password='$pass'";
	$run_user = mysqli_query($mysqli, $get_user);
	$check = mysqli_num_rows($run_user);
	if ($check == 1){
		$_SESSION['username'] = $name;
		echo "<script>window.location.href = 'recipes.php'</script>";
	}
	else{
		echo "<script>alert('Username or Password is incorrect')</script>";
		echo "<script>window.location.href = 'home.html'</script>";
	}
}


?>