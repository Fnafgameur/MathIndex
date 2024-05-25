const choosePrompt = document.getElementById("choosePrompt")??null;
const chooseButton = document.getElementById("chooseButton")??null;
const chooseInput = document.getElementById("chooseInput")??null;

if (choosePrompt !== null) {

    addEventListener("click",function(){

        if (event.target === chooseButton) {
            if (choosePrompt.style.display === "none") {
                choosePrompt.style.display = "flex";
            } else {
                choosePrompt.style.display = "none";
            }
        } else if (event.target !== choosePrompt && !choosePrompt.contains(event.target)){
            choosePrompt.style.display = "none";
        }
    });

    addEventListener("keypress",function(){
        if (event.keyCode === 13){
            if (chooseInput === null || chooseInput.value.trim() === ""){
                return;
            }

            let value = chooseInput.value.trim();

            if (isNaN(value)){
                return;
            }

            let url = window.location.href;
            let newUrl = url.replace(/pagination=\d+/,"pagination="+value);

            console.log(newUrl)

            if (url === newUrl){
                return;
            }

            window.location.replace(newUrl);
        }
    });
}

