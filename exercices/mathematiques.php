<?php
    $exercices = get_exercices();

?>
<div class="mathematics">
    <h1 class="mathematics__title">Exercices</h1>
    <div class="mathematics__content">
        <h2 class="mathematics__content-title"></h2>
        <table class="mathematics__table">
            <thead class="mathematics__table-head">
            <tr class="mathematics__table-row">
                <th class="mathematics__table-heading">Nom</th>
                <th class="mathematics__table-heading">Thématiques</th>
                <th class="mathematics__table-heading">Difficulté</th>
                <th class="mathematics__table-heading">Durée</th>
                <th class="mathematics__table-heading">Mots-clés</th>
                <th class="mathematics__table-heading">Fichiers</th>
            </tr>
            </thead>
            <tbody class="mathematics__table-body">
            <?php foreach ($exercices as $exercice) { ?>
                <tr class="mathematics__table-row">
                    <td class="mathematics__table-data"><?=$exercice['name']?></td>
                    <td class="mathematics__table-data"><?=$exercice['name']?></td>
                    <td class="mathematics__table-data"><?=$exercice['name']?></td>
                    <td class="mathematics__table-data"><?=$exercice['name']?></td>
                    <td class="mathematics__table-data"><?=$exercice['name']?></td>
                    <td class="mathematics__table-data"><?=$exercice['name']?></td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
</div>