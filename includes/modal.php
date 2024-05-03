<?php
    switch ($_GET["page"]) {
        case Page::MES_EXERCICES->value:
            $nom_page = "cet exercice";
            break;
        case Page::ADMINISTRATION->value:
            $nom_page = "ce contributeur";
            break;
        default:
            $nom_page = "cet élément";
    }

    if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])))
    {

    }


?>





<div class="modal__container">

    <div class="overlay modal__trigger"></div>

    <div class="modal" role="dialog" aria-labelledby="modal__title" aria-describedby="modal__paragraph">
        <button aria-label="close modal" class="close__modal modal__trigger">
            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.59827 4.43936L9.48733 0.550293L10.5983 1.66123L6.7092 5.55029L10.5983 9.43936L9.48733 10.5503L5.59827 6.66123L1.7092 10.5503L0.598267 9.43936L4.48733 5.55029L0.598267 1.66123L1.7092 0.550293L5.59827 4.43936Z" fill="#4F4F4F"/>
            </svg>
        </button>
        <div class="modal__header">
            <div class="modal__images">
                <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.5983 26.2169C17.8199 26.2169 20.7366 24.911 22.8479 22.7998C24.9591 20.6885 26.265 17.7718 26.265 14.5502C26.265 11.3286 24.9591 8.41191 22.8479 6.30063C20.7366 4.18938 17.8199 2.88354 14.5983 2.88354C11.3767 2.88354 8.46001 4.18938 6.34873 6.30063C4.23748 8.41191 2.93164 11.3286 2.93164 14.5502C2.93164 17.7718 4.23748 20.6885 6.34873 22.7998C8.46001 24.911 11.3767 26.2169 14.5983 26.2169Z" stroke="#4F4F4F" stroke-width="2" stroke-linejoin="round"/>
                    <path d="M9.93164 14.5503L13.4316 18.0503L20.4316 11.0503" stroke="#4F4F4F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="modal__textContainer">
                <h1 id="modal__title">Confirmez la suppression</h1>
                <p id="modal__paragraph">Êtes-vous certain de vouloir supprimer <?=$nom_page?> ?</p>
            </div>
        </div>
        <form action="" method="POST">
            <div class="modal__buttons">
                <button class="btn btn--bglightgrey btn--textgrey btn--paddingmodal btn--border-radius modal__trigger">Annuler</button>
                <button class="btn btn--bgdarkgrey btn--paddingmodal btn--border-radius">Confirmer</button>
            </div>
        </form>
    </div>
</div>

