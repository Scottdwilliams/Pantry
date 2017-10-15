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

$username = $_SESSION['username'];   #This is usually easier to do here.

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
        <li class="active"><a href="recipes.php">Recipes<span class="sr-only">(current)</span></a></li>
      </ul>
      <ul class="nav navbar-nav">
        <li><a href="pantry.php">Pantry<span class="sr-only"></span></a></li>
      </ul>
      <form class="navbar-form navbar-right" role="button" name="logout" action="home.html" method="POST">
        <button type="submit" class="btn btn-default">Logout</button>
      </form>
    </div>
  </nav>;

<div class="well-lg">
    <div class="col-lg-3">
      <form action="recipes.php" method="POST">
    <div class="form-group">
        <input type="text" class="form-control" name="cost" placeholder="Search by price per person...">
    </div>
      <button type="submit" class="btn btn-default">Search</button>
      </form>
    </div>
    <!-- Add more search functions here -->
 </div><!-- well -->

 <?php
    if (!isset($_POST["cost"]) || $_POST['cost'] == NULL) {
      $filter = 'No filter';
    }
    else {
      $filter = $_POST["cost"];
    }
 ?>
  <div class="container">
  <table class="table table-bordered">
    <tr>
      <td class='col-md-2'><h4>Recipe Name</h4></td><td class='col-md-1'><h4>Price</h4></td><td class='col-md-1'><h4>Pantry Price</h4></td><td class='col-md-1'><h4>Meal Type</h4></td><td class='col-md-1'><h4>Rating</h4></td>
    </tr>
  <?php
    if ($filter != 'No filter') {
      $filtering = "SELECT R.id as recipe, R.title, R.price, (SELECT (r.price - SUM(i.price)) FROM ingredient i INNER JOIN recipe_main_ingredient rmi ON rmi.ingredient_id = i.id INNER JOIN recipe r ON rmi.recipe_id = r.id INNER JOIN pantry p ON i.id = p.ingredient_id INNER JOIN users u ON u.id = p.user_id WHERE u.username ='".$_SESSION['username']."' AND r.id = recipe) as difference, M.name, R.rating FROM recipe R INNER JOIN meal_type M ON R.type = M.id WHERE price <= '".$filter."' ORDER BY rating DESC, price DESC";
    }
    else {
      $filtering = "SELECT R.id as recipe, R.title, R.price, (SELECT (r.price - SUM(i.price)) FROM ingredient i INNER JOIN recipe_main_ingredient rmi ON rmi.ingredient_id = i.id INNER JOIN recipe r ON rmi.recipe_id = r.id INNER JOIN pantry p ON i.id = p.ingredient_id INNER JOIN users u ON u.id = p.user_id WHERE u.username ='".$_SESSION['username']."' AND r.id = recipe) as difference, M.name, R.rating FROM recipe R INNER JOIN meal_type M ON R.type = M.id ORDER BY rating DESC, price DESC";
    }

    $dbTable = $mysqli->query($filtering);
    if ($dbTable->num_rows > 0) {
      while ($row = $dbTable->fetch_row()) {
        $idNum = $row[0];
        if ($row[3] == NULL || $row[3] < 0){
          $row[3] = $row[2];
        }
        echo "<tr><td class='col-md-2'><a href='view_recipe.php?id=".$row[0]."'>".$row[1]."</a></td><td class='col-md-1'>$".$row[2]."</td><td class='col-md-1'>$".$row[3]."</td><td class='col-md-1'>".$row[4]."</td><td class='col-md-1'>".$row[5]."</td>";
      }
    }
  ?>
  </tr>
  </table>
  </div>
  <br><br>
</div>
</body></html>
