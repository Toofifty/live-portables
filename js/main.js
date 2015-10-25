// toggle portables menu
function selectPortable() {
  if (document.getElementById("sel").getAttribute("data-showopts") == "false") {
    document.getElementById("sel").setAttribute("data-showopts", "true");
  } else {
    document.getElementById("sel").setAttribute("data-showopts", "false");
  }
}