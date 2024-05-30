// Javascript of the modal
const modalContainer = document.querySelector(".modal__container");
const modalTriggers = document.querySelectorAll(".modal__trigger");

modalTriggers.forEach(trigger => trigger.addEventListener("click", toggleModal));

function toggleModal(){
    modalContainer.classList.toggle("modal__container--active");
}
function sendData(element){
    document.querySelector('.modal_delete_form input[name="delete"]').value = element.querySelector('input[name="delete"]').value;
}
