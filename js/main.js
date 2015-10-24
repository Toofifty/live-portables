// auto-updater
$("document").ready(function(){
  var interval = setInterval(update(), 500);
  function update() {
    $("#data").load("data.php");
  }
});

// toggle portables menu
function selectPortable() {
  if (document.getElementById("sel").getAttribute("data-showopts") == "false") {
    document.getElementById("sel").setAttribute("data-showopts", "true");
  } else {
    document.getElementById("sel").setAttribute("data-showopts", "false");
  }
}