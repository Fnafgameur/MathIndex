

var information = document.getElementById("submit__information");
var source = document.getElementById("submit__source");
var file = document.getElementById("submit__file");

var btn_i = document.getElementById("btn_information");
var btn_s = document.getElementById("btn_source");
var btn_f = document.getElementById("btn_file");
var next_s = document.getElementById("next_source");
var next_f = document.getElementById("next_file");

var btn_exo = document.getElementById("fichier_exercice");
var btn_cor = document.getElementById("fichier_correction");
var txt_exo = document.getElementById("fichier_exercice_choisit");
var txt_cor = document.getElementById("fichier_correction_choisit");

btn_i.addEventListener("click",function(){
    if (information.style.display === "none"){
        information.style.display = "block";
        source.style.display = "none";
        file.style.display = "none";
    }
    else{
        information.style.display = "block";
        source.style.display = "none";
        file.style.display = "none";
    }
});

btn_s.addEventListener("click",function(){
    if (source.style.display === "none"){
        source.style.display = "block";
        information.style.display = "none";
        file.style.display = "none";
    }
    else{
        source.style.display = "block"
        information.style.display = "none";
        file.style.display = "none";
    }
});

next_s.addEventListener("click",function(){
    if (source.style.display === "none"){
        source.style.display = "block";
        information.style.display = "none";
        file.style.display = "none";
    }
    else{
        source.style.display = "block"
        information.style.display = "none";
        file.style.display = "none";
    }
});

btn_f.addEventListener("click",function(){
    if (file.style.display === "none"){
        file.style.display = "block";
        information.style.display = "none";
        source.style.display = "none";
    }
    else{
        file.style.display = "block";
        source.style.display = "none"
        information.style.display = "none";
    }
});

next_f.addEventListener("click",function(){
    if (file.style.display === "none"){
        file.style.display = "block";
        information.style.display = "none";
        source.style.display = "none";
    }
    else{
        file.style.display = "block";
        source.style.display = "none"
        information.style.display = "none";
    }
});


btn_exo.addEventListener('change', function(){
    txt_exo.textContent = btn_exo.files[0].name;
});


btn_cor.addEventListener('change', function(){
    txt_cor.textContent = btn_cor.files[0].name;
});