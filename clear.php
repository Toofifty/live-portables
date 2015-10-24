<?php
  session_start();
  $_SESSION['owned'] = array();
  $_SESSION['voted'] = array();
  echo "<meta http-equiv='refresh' content='0; url=index.php' />";
?>