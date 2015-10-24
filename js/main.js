// auto-updater
$("document").ready(function(){
  var interval = setInterval(update(), 5000);
  function update() {
    $("#data").load("data.php");
  }
});
