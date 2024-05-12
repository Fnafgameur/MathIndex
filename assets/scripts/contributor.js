
var btn_pic = document.getElementById("profilepic");
var txt_pic = document.getElementById("profilepic_choisit");

btn_pic.addEventListener('change', function(){
    txt_pic.textContent = btn_pic.files[0].name;
});