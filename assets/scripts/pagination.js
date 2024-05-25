const choosePrompt = document.getElementById("choosePrompt")??null;
const chooseButton = document.getElementById("chooseButton")??null;
const chooseInput = document.getElementById("chooseInput")??null;
const chooseConfirm = document.getElementById("chooseConfirm")??null;

function sendPaginationRequest(value) {

    value = chooseInput.value.trim();

    if (isNaN(value)) {
        return;
    }

    let url = window.location.href;

    if (url.includes("?pagination=")) {

        let newUrl = url.replace(/\?pagination=\d+/,"?pagination="+value);

        if (url === newUrl){
            return;
        }

        window.location.replace(newUrl);
        return;
    }
    if (url.includes("&pagination=")) {

        let newUrl = url.replace(/&pagination=\d+/,"&pagination="+value);

        console.log(url);
        console.log(newUrl);

        if (url === newUrl){
            return;
        }

        window.location.replace(newUrl);
        return;
    }
    if (url.includes("?")) {

        if (url.endsWith("#")) {
            url = url.slice(0,-1);
        }

        let newUrl = url + "&pagination=" + value;

        window.location.replace(newUrl);
    }
}

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
        } else if (event.target === chooseConfirm) {
            let value = chooseInput.value.trim();

            if (value === "") {
                return;
            }
            sendPaginationRequest(value);
        }
    });

    addEventListener("keypress",function(){
        if (event.keyCode === 13) {
            let value = chooseInput.value.trim();

            if (value === ""){
                return;
            }
            sendPaginationRequest(value);
        }
    });
}

