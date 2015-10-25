<?php
  session_start();
  
  if (isset($_GET['id'])) {
    // database connections
    $db = "db/data.db";
    $con = new PDO('sqlite:' . $db);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    
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
      
      echo "<p>Vote successful. Redirecting...</p>";
      
    } else if (in_array($id, $_SESSION['owned']) || $p->downs >= 5) {
      // user owns this portable, remove it completely
      
      $q = "DELETE FROM portables WHERE id=$id";
      $sth = $con->prepare($q);
      $sth->execute();
      
      echo "<p>Removal successful. Redirecting...</p>";
      
    } else {
      // user is downvoting this portable
      
      $d = $p->downs + 1;
      $q = "UPDATE portables SET downs=$d WHERE id=$id";
      $sth = $con->prepare($q);
      $sth->execute();
      
      // add to voted array
      array_push($_SESSION['voted'], $id);
      
      echo "<p>Vote successful. Redirecting...</p>";
    }
    echo "<meta http-equiv='refresh' content='0; 
        url=data.php?p=".$_SESSION['page']."' />";
  } else {
    echo "<p>Invalid data. Redirecting in 5 seconds...</p>";
    echo "<meta http-equiv='refresh' content='5; url=data.php?p=all' />";
  }
?>