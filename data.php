<?php
  // database connections
  $db = "db/data.db";
  $con = new PDO('sqlite:' . $db);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  
  session_start();
  if (!isset($_SESSION['owned'])) {
      $_SESSION['owned'] = array();
      $_SESSION['voted'] = array();
  }
?>
<html>
<head>
  <!--<meta http-equiv="refresh" content="20">-->
  <link rel="stylesheet" type="text/css" href="css/portable.css">
</head>
<body>
    <div class="portable header">
        <img src='img/forge.png'><p class='type'>Portable type</p><p class='owner'>Host</p><p class='world'>World</p><p class='location'>Location</p><p class='downs'>Dead</p>
    </div>
<?php
    function check_owned($id) {
        foreach ($_SESSION['owned'] as $oid) {
            if ($oid == $id) {
                return true;
            }
        }
        return false;
    }


  // build table
  if (!isset($_GET['p']) || $_GET['p'] == 'all') {
    $q = "SELECT * FROM portables ORDER BY downs ASC";
    $p = '';
  } else {
    $q = "SELECT * FROM portables WHERE type='".$_GET['p']."' ORDER BY downs ASC";
    $p = " ".$_GET['p'];
  }
  $sth = $con->prepare($q);
  $sth->execute();
  
  $portables = $sth->fetchALL();
  
  foreach($portables as $record) {
    if ($record->downs >= 5) {
      $d = "red";
    } else {
      $d = "";
    }
    echo "<div class='portable'>";
    echo "<img src='img/".$record->type.".png'><p class='type'>".$record->type."</p>";
    echo "<p class='owner'>".$record->owner."</p>";
    echo "<p class='world'>".$record->world."</p>";
    echo "<p class='location'>".$record->location."</p>";
    if (!in_array($record->id, $_SESSION['voted']) || in_array($record->id, $_SESSION['owned'])) {
        echo "<a href='down.php?id=".$record->id."' target='_top'>";
        if (in_array($record->id, $_SESSION['owned'])) {
            echo "<img class='thumb' src='img/remove.png' title=\"Remove this portable when you've finished hosting.\">";
        } else {
            echo "<img class='thumb' src='img/thumbs_down.png' title=\"Only click this if you couldn't find the portable.\">";
        }
        echo "<p class='downs ".$d."'>".$record->downs."</p>";
        echo "</a>";
    } else {
        echo "<img class='thumb' src='img/voted.png' title=\"You have already voted on that portable.\">";
        echo "<p class='downs ".$d."'>".$record->downs."</p>";
    }
    echo "</div>";
  }
  
  if (count($portables) == 0) {
    echo "<div class='error'>";
    if ($p == " box") {
      echo "<p>No portable deposit boxes found.</p>";
    } else {
     echo "<p>No portable".$p."s found.</p>";
    }
    echo "</div>";
  }
?>
</body>
</html>