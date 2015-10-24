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
    if (!empty($_GET) && isset($_GET['p'])) { 
      $port_data = "data.php?p=".$_GET['p'] ; 
      $p = $_GET['p'];
    } else { 
      $port_data = "data.php?p=all"; 
      $p = "all";
    }
  ?>
</head>
<body>
  <header>
    <ul id="nav">
      <li><a class="navb" href="index.php">Live Portables</a></li>
      <li><a class="navb" href="http://github.com/Toofifty/live-portables">Source</a></li>
    </ul>
  </header>
  <!--dynamic content-->
  <div class="content">
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
      <li><a class="selb <?php if ($p=='box') echo 'active';?>" href="?p=box">Deposit Box</a></li>
    </ul>
    <iframe id="dynamic" src="<?php echo $port_data ?>"></iframe>
    <form method="post" action="createhost.php">
      <h4>Host</h3>
      <select name="type">
          <option value="brazier">Brazier</option>
          <option value="crafter">Crafter</option>
          <option value="fletcher">Fletcher</option>
          <option value="forge">Forge</option>
          <option value="range">Range</option>
          <option value="sawmill">Sawmill</option>
          <option value="well">Well</option>
          <option value="box">Deposit Box</option>
      </select>
      <input type="text" name="host" placeholder="host username"></input>
      <input type="text" name="location" placeholder="location" required></input>
      <input type="number" name="world" placeholder="world" min=1 max=140 required></input>
      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
