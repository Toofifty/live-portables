<?php
  // database connections
  $db = "db/data.db";
  $con = new PDO('sqlite:' . $db);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  
  session_start();
  
  // initialize session arrays if not found
  if (!isset($_SESSION['owned'])) {
      $_SESSION['owned'] = array();
      $_SESSION['voted'] = array();
      $_SESSION['page'] = "all";
  }
  
  // for voting data
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  
    // grab selected portable from array
    $q = "SELECT * FROM portables WHERE id=$id";
    $sth = $con->prepare($q);
    $sth->execute();
    
    $p = $sth->fetch();
    
    if (in_array($id, $_SESSION['voted'])) {
      // user is undoing their downvote
      $d = $p->downs - 1;
      $q = "UPDATE portables SET downs=$d WHERE id=$id";
      $sth = $con->prepare($q);
      $sth->execute();
      
      // remove from voted array (allow user to re-downvote)
      $k = array_search($id, $_SESSION['voted']);
      if ($k !== false) {
        unset($_SESSION['voted'][$k]);
      }
      
    } else if (in_array($id, $_SESSION['owned']) || $p->downs >= 5) {
      // user owns this portable, remove it completely
      
      $q = "DELETE FROM portables WHERE id=$id";
      $sth = $con->prepare($q);
      $sth->execute();
      
    } else {
      // user is downvoting this portable
      
      $d = $p->downs + 1;
      $q = "UPDATE portables SET downs=$d WHERE id=$id";
      $sth = $con->prepare($q);
      $sth->execute();
      
      // add to voted array
      array_push($_SESSION['voted'], $id);
    }
  }
?>
<html>
<head>
  <?php
    if (isset($_GET['au'])) {
      echo '<meta http-equiv="refresh" content="'.$_GET['au'].'">';
    }
  ?>
  <!--<meta http-equiv="refresh" content="20">-->
  <link rel="stylesheet" type="text/css" href="css/portable.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script>
    // script to remove highlighted portable after 1s
    $("document").ready(function() {
      setTimeout(function() {
        $(".recent").removeClass("recent");
      }, 1000);
    });
  </script>
</head>
<body>
  <div class="portable header">
    <img src='img/forge.png'><p class='type'>Portable type</p><p class='owner'>Host</p><p class='world'>World</p><p class='location'>Location</p><p class='age'>Age</p><p class='downs'>Votes</p>
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
  
  // create a '15mins' age from a starting time
  function age($t) {
    $diff = time() - $t;
    $d = floor($diff / 86400);
    $h = floor(($diff % 86400) / 3600);
    $m = floor(($diff % 3600) / 60);
    $s = floor($diff % 60);
    if ($d != 0) {
      return "$d days";
    } else if ($h != 0) {
      return "$h hours";
    } else if ($m != 0) {
      return "$m min";
    } else {
      return "$s secs";
    }
  }


  // build table
  if (!isset($_GET['p']) || $_GET['p'] == 'all') {
    // no specific type found, get all
    $q = "SELECT * FROM portables ORDER BY downs ASC";
    $p = '';
  } else {
    // get specific types of portables
    $q = "SELECT * FROM portables WHERE type='".$_GET['p']."' 
      ORDER BY downs ASC";
    $p = " ".$_GET['p'];
  }
  $sth = $con->prepare($q);
  $sth->execute();
  
  $portables = $sth->fetchALL();
  
  // iterate over portables and build table
  foreach($portables as $record) {
    // high amounts of downvotes are coloured red
    $d = $record->downs >= 3 ? "red" : "";
    // add class for animation on recently changed record
    $r = isset($_GET['id']) && $record->id == $_GET['id'] ? "recent" : "";
    echo "<div class='portable ".$r."'>";
    echo "<img src='img/".$record->type.".png'>";
    echo "<p class='type'>".$record->type."</p>";
    echo "<p class='owner'>".$record->owner."</p>";
    echo "<p class='world'>".$record->world."</p>";
    echo "<p class='location'>".$record->location."</p>";
    echo "<p class='age'>".age($record->created)."</p>";
    
    // build vote button
    echo "<a href='data.php?p=".$_SESSION['page']."&id=".$record->id."'>";
    if (in_array($record->id, $_SESSION['owned'])) {
      // user owns this portable, always display red x
      echo "<img class='thumb' src='img/remove.png' 
        title=\"Remove this portable when you've finished hosting\">";
    } else if (in_array($record->id, $_SESSION['voted'])) {
      // user has already voted this portable down, display undo button
      echo "<img class='thumb' src='img/thumbs_up.png' 
        title=\"Undo downvote\">";
    } else {
      // user has not voted and does not own it, display downvote button
      echo "<img class='thumb' src='img/thumbs_down.png' 
        title=\"Only click this if you couldn't find the portable\">";
    }
    echo "<p class='downs ".$d."'>".$record->downs."</p>";
    echo "</a>";
    echo "</div>";
  }
  
  // echo no portables found error
  if (count($portables) == 0) {
    echo "<div class='error'>";
    echo "<p>No portable".$p."s found.</p>";
    echo "</div>";
  }
?>
</body>
</html>