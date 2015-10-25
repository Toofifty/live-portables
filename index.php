<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Live RS Portables</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <?php
    session_start();
  
    // get portable type from $_GET
    if (!empty($_GET) && isset($_GET['p'])) { 
      $port_data = "data.php?p=".$_GET['p'] ; 
      $p = $_GET['p'];
    } else { 
      $port_data = "data.php?p=all"; 
      $p = "all";
    }
    
    if (isset($_GET['au'])) {
      $port_data = $port_data."&au=".$_GET['au'];
    }
    
    // track current page
    $_SESSION['page'] = $p;
  ?>
</head>
<body>
  <!-- navigation -->
  <header>
    <ul id="nav">
      <li><a class="navb" href="index.php">Live Portables</a></li>
      <!--<li><a class="navb" href="quick.php">Quick Chart</a></li>-->
      <li><a class="navb" href="http://github.com/Toofifty/live-portables">Source</a></li>
    </ul>
  </header>
  
  <!-- content -->
  <div class="content">
    <!-- portable type selection -->
    <ul id="sel" onclick="selectPortable()" data-showopts="false">
      <li><a class="selb" href="#"><?php echo ucfirst($p); ?></a></li>
      <li><a class="selb <?php if ($p=='all') echo 'active';?>" href="?p=all">All</a></li>
      <li><a class="selb <?php if ($p=='brazier') echo 'active';?>" href="?p=brazier">Brazier</a></li>
      <li><a class="selb <?php if ($p=='crafter') echo 'active';?>" href="?p=crafter">Crafter</a></li>
      <li><a class="selb <?php if ($p=='fletcher') echo 'active';?>" href="?p=fletcher">Fletcher</a></li>
      <li><a class="selb <?php if ($p=='forge') echo 'active';?>" href="?p=forge">Forge</a></li>
      <li><a class="selb <?php if ($p=='range') echo 'active';?>" href="?p=range">Range</a></li>
      <li><a class="selb <?php if ($p=='sawmill') echo 'active';?>" href="?p=sawmill">Sawmill</a></li>
      <li><a class="selb <?php if ($p=='well') echo 'active';?>" href="?p=well">Well</a></li>
    </ul>
    <!-- portable data (dynamic) -->
    <iframe src="<?php echo $port_data; ?>"></iframe>
    <!-- hosting form -->
    <form method="post" action="createhost.php">
      <h4>Host</h3>
      <select name="type">
          <option <?php if ($p == "brazier") echo "selected"; ?> value="brazier">Brazier</option>
          <option <?php if ($p == "crafter") echo "selected"; ?> value="crafter">Crafter</option>
          <option <?php if ($p == "fletcher") echo "selected"; ?> value="fletcher">Fletcher</option>
          <option <?php if ($p == "forge") echo "selected"; ?> value="forge">Forge</option>
          <option <?php if ($p == "range") echo "selected"; ?> value="range">Range</option>
          <option <?php if ($p == "sawmill") echo "selected"; ?> value="sawmill">Sawmill</option>
          <option <?php if ($p == "well") echo "selected"; ?> value="well">Well</option>
      </select>
      <input type="text" pattern="[A-Za-z0-9 _]*" name="host" placeholder="host username"></input>
      <input type="text" pattern="[A-Za-z0-9 .,-]*" name="location" placeholder="location" required></input>
      <input type="number" name="world" placeholder="world" min=1 max=140 required></input>
      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
