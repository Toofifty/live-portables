<?php
  session_start();

  if (isset($_POST['host']) && isset($_POST['location']) 
      && isset($_POST['world'])) {
    $host = $_POST['host'];
    if ($host == "") {
      $host = "Anonymous";
    }
    
    $q = "INSERT INTO portables(type, owner, world, location, created) VALUES
      ('".$_POST['type']."',
      '".$host."',
      '".$_POST['world']."',
      '".$_POST['location']."',
      '".time()."'
    )";
    
    // database connections
    $db = "db/data.db";
    $con = new PDO('sqlite:' . $db);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    
    $sth = $con->prepare($q);
    $sth->execute();
    
    $q = "SELECT * FROM portables";
    $sth = $con->prepare($q);
    $sth->execute();
    
    $id = 0;
    
    foreach($sth->fetchAll() as $rec) {
      if ($rec->id > $id) {
        $id = $rec->id;
      }
    }
    
    array_push($_SESSION['owned'], $id++);
    
    echo "<meta http-equiv='refresh' content='0; 
      url=index.php?p=".$_POST['type']."' />";
  } else {
    echo "<p>Invalid data. Redirecting in 5 seconds...</p>";
    echo "<meta http-equiv='refresh' content='5; url=index.php' />";
  }
?>