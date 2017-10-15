<?php
ini_set('display_errors', 'On');

$db_username = 'xxxxxxxxxxx-db'; //onid username
$db_password = 'xxxxxxxxxxxxxx'; //db password
$db_name = 'xxxxxxxxx-db';       //db username (same as onid)
$db_host = 'oniddb.cws.oregonstate.edu';

$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);
if ($mysqli->connect_errno){
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$recipe_id = $_GET["id"];

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
        <li><a href="pantry.php">Pantry<span class="sr-only"></span></a></li>
      </ul>
      <form class="navbar-form navbar-right" role="button" action="home.html" method="POST">
        <button type="submit" class="btn btn-default">Logout</button>
      </form>
    </div>
  </nav>;

<?php

$display_recipe = "SELECT R.id, R.title, R.price, M.name, R.rating, R.instructions, R.optional FROM recipe R INNER JOIN meal_type M ON R.type = M.id WHERE R.id = '".$recipe_id."'";
$dbTable = $mysqli->query($display_recipe);

echo "<div class='container'><table><tr>";
if ($dbTable->num_rows > 0) {
    while ($row = $dbTable->fetch_row()) {
      $idNum = $row[0];
      echo "<td><b>Recipe Title:</b> ".$row[1]."</td></tr>";
      echo "<td><b>Price:</b> $".$row[2]."</td></tr>";
      echo "<td><b>Meal Type:</b> ".$row[3]."</td></tr>";
      echo "<td><b>Rating:</b> ".$row[4]."</td></tr>";
      echo "<td><b>Recipe Instructions:</b><br> ".$row[5]."</td></tr>";
      echo "<td><b>Optional Ingredient:</b> ".$row[6]."</td></tr>";
    }
}
echo "</table></div><p></p>";
echo "<div class='container'>
        <form role='button' action='recipes.php' method='POST'>
          <button type='submit' class='btn btn-default'>Back to recipes</button>
        </form>
      </div>";
?>
