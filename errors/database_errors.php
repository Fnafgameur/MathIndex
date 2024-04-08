<?php

function database_not_found(): void { ?>

    <div class="error__container">
        <h4 class="error__type">Erreur de connexion à la base de données</h4>
        <p class="error__desc">Une erreur est survenue lors de la connexion à la base de données. Veuillez contacter l'administrateur du site.</p>
    </div>

<?php exit(); } ?>