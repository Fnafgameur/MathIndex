<?php
    $exercices = get_exercices();
    $exercices_sorted = get_exercices_sorted();
?>
<div class="mathematics">
    <h1 class="mathematics__title">Exercices</h1>
    <div class="mathematics__content">
        <h2 class="mathematics__content-title">Nouveautés</h2>
        <div>
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
            <?php foreach ($exercices_sorted as $exercice_sorted) {
                $file = get_file_by_exercices($exercice_sorted['exercise_file_id']);
                ?>
                <tr class="mathematics__table-row">
                    <td class="mathematics__table-data"><?=$exercice_sorted['name']?></td>
                    <td class="mathematics__table-data"><?=get_thematic_by_exercices($exercice_sorted["thematic_id"])['name'];?>
                    </td>
                    <td class="mathematics__table-data"><?=$exercice_sorted['difficulty']?></td>
                    <td class="mathematics__table-data"><?=$exercice_sorted['duration']??"NULL gros gay"?></td>
                    <td class="mathematics__table-data"><?=$exercice_sorted['keywords']??"NULL gros gay"?></td>
                    <td class="mathematics__table-data">
                        <a class="link link--row" href="" download="<?=$file['name'].'.'.$file['extension']?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                        <a class="link link--row" href="" download="<?=$file['name'].'_corrigé.'.$file['extension']?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
        <div>
            <h2 class="mathematics__content-title">Tous les exercices</h2>
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
                <?php foreach ($exercices as $exercice) {
                    $file = get_file_by_exercices($exercice['exercise_file_id']);
                    ?>
                    <tr class="mathematics__table-row">
                        <td class="mathematics__table-data"><?=$exercice['name']?></td>
                        <td class="mathematics__table-data"><?=get_thematic_by_exercices($exercice["thematic_id"])['name'];?>
                        </td>
                        <td class="mathematics__table-data"><?=$exercice['difficulty']?></td>
                        <td class="mathematics__table-data"><?=$exercice['duration']??"NULL gros gay"?></td>
                        <td class="mathematics__table-data"><?=$exercice['keywords']??"NULL gros gay"?></td>
                        <td class="mathematics__table-data">
                            <a class="link link--row" href="" download="<?=$file['name'].'.'.$file['extension']?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                            <a class="link link--row" href="" download="<?=$file['name'].'_corrigé.'.$file['extension']?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>