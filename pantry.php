<?php

/*************************************************************************
* 
* Class: CS 340
*
* Notes: Search for recipes via rating/price/ingredient.
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

$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
	<title>Recipe Database</title>
	<meta name="description" content="Recipe Database">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="dashboard.css" rel="stylesheet">
    </head>
    <body>  

  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="recipes.php">Recipes<span class="sr-only"></span></a></li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="active"><a href="pantry.php">Pantry<span class="sr-only">(current)</span></a></li>
      </ul>
      <form class="navbar-form navbar-right" role="button" action="home.html" method="POST">
        <button type="submit" class="btn btn-default">Logout</button>
      </form>
    </div>
  </nav>;

<?php

if (isset($_POST["addToPantry"])) {
  addToPantry();
}
if (isset($_POST["removeFromPantry"])) {
  removeFromPantry();
}

function addToPantry() {
  global $mysqli;
  $ingredient = $_POST["pantry_ingredient_add"];
  $username = $mysqli->real_escape_string($_SESSION['username']);

  if(!($adding = $mysqli->prepare("INSERT INTO pantry (user_id, ingredient_id) VALUES ((SELECT U.id FROM users U WHERE U.username = ?),(SELECT I.id FROM ingredient I WHERE I.title = ? ))"))) {
    echo "Prepare failed.";
  }

  if (!$adding->bind_param("ss", $username, $ingredient)) {
    echo "Binding parameters failed.";
  }

  if (!$adding->execute()) {
    exit(1);
  }

  echo "<meta http-equiv=\"refresh\" content=\"0;URL=pantry.php\">";
}

function removeFromPantry() {
  global $mysqli;
  $user_id = $_POST["user_id"];
  $ingredient_id = $_POST["ingredient_id"];

  $remove = $mysqli->prepare("DELETE FROM pantry WHERE user_id = ? && ingredient_id = ?");
  $remove->bind_param("ii", $user_id, $ingredient_id);
  $remove->execute();
  $remove->close();

  echo "<meta http-equiv=\"refresh\" content=\"0;URL=pantry.php\">";
}
?>

<div align="center">
  <form method="POST" action="pantry.php">
    <label><select name="pantry_ingredient_add" class="form-control">
        <?php
          $display_pantry_ingredients = "SELECT DISTINCT title FROM ingredient ORDER BY title ASC";
          if ($all = $mysqli->query($display_pantry_ingredients)) {
            while ($row = $all->fetch_row()) {
              echo '<option name="pantry_ingredient_add" value="'.$row[0].'">'.$row[0].'</option>';
            }
          }
          $all->close();
        ?>
      </select></label>
    <input class="btn btn-default" type="submit" value="Add Ingredient to Pantry" name="addToPantry">
  </form>
</div>
<br><br>
<div class="container">
<table class="table table-bordered">
  <tr>
    <td class='col-md-1'><h4>Pantry Ingredient</h4></td><td class='col-md-1'><h4>Remove</h4></td>
  </tr>
<?php
  $filtering = "SELECT P.user_id, P.ingredient_id, I.title FROM pantry P INNER JOIN ingredient I ON P.ingredient_id = I.id INNER JOIN users U ON U.id = P.user_id WHERE U.username = '".$username."' ORDER BY I.title ASC";
  $dbTable = $mysqli->query($filtering);

  if ($dbTable->num_rows > 0) {
    while ($row = $dbTable->fetch_row()) {
      $uid = $row[0];
      $ing_id = $row[1];
      echo "<tr><td class='col-md-1'>".$row[2]."</td>";
      echo "<form action='pantry.php' method='POST'><input type='hidden' name='user_id' value='$uid'><input type='hidden' name='ingredient_id' value='$ing_id'><td class='col-md-1'><input type='submit' name='removeFromPantry' value='✖'></form></td></tr>";
    }
  }
?>
</table>
</div>