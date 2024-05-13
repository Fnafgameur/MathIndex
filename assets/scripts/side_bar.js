
var btn_bar = document.getElementById("move_side-bar");
var side_bar = document.getElementById("side-bar");

btn_bar.addEventListener('click', function(){
    if ( side_bar.style.marginLeft == "-330px"){
        side_bar.style.marginLeft = "0";
    } else {
        side_bar.style.marginLeft = "-330px";
    }
});
