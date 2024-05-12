<?php
    $exercises_sorted = get_exercises_sorted();
    $current_page = get_current_page();
    $nb_exercises = get_exercises()["number"];
    $per_page = 5;
    $pages = ceil($nb_exercises / $per_page);
    $exercises = get_exercises_with_limit($current_page, $per_page)["exercises"];
?>
<div class="mathematics">
    <h1 class="mathematics__title">Exercices</h1>
    <div class="mathematics__content">
        <h2 class="mathematics__content-title">Nouveautés</h2>
        <div class="mathematics__content-table">
            <table class="mathematics__table">
                <thead class="mathematics__table-head">
                <tr class="mathematics__table-row">
                    <th class="mathematics__table-heading name">Nom</th>
                    <th class="mathematics__table-heading thematic">Thématiques</th>
                    <th class="mathematics__table-heading difficulty">Difficulté</th>
                    <th class="mathematics__table-heading duration">Durée</th>
                    <th class="mathematics__table-heading keywords">Mots-clés</th>
                    <th class="mathematics__table-heading files">Fichiers</th>
                </tr>
                </thead>
                <tbody class="mathematics__table-body">
                <?php foreach ($exercises_sorted as $exercise_sorted) {
                    $file_sorted = get_file_by_exercises($exercise_sorted['exercise_file_id']);
                    $exercice = $file_sorted['exercise'];
                    $correction = $file_sorted['correction'];
                    $keywords_sorted = explode(' ', $exercise_sorted['keywords']);
                    $duration = $exercise_sorted["duration"];
                    if (substr($duration, -2) === ".5") {
                        $duration = str_replace(".5", "h30", $duration);
                    }
                    else {
                        if (floor($duration) == $duration) {
                            $duration .= "h00";
                        }
                    }
                    ?>
                    <tr class="mathematics__table-row">
                        <td class="mathematics__table-data"><?=$exercise_sorted['name']?></td>
                        <td class="mathematics__table-data"><?=get_thematic_by_exercises($exercise_sorted["thematic_id"])['name'];?>
                        </td>
                        <td class="mathematics__table-data">Niveau <?=$exercise_sorted['difficulty']?></td>
                        <td class="mathematics__table-data"><?=$duration??"Aucun"?></td>
                        <td class="mathematics__table-data wordbreak"><?php foreach ($keywords_sorted as $keyword) {
                                echo "<p class='keyword'>$keyword</p>";
                            } ?>
                        </td>
                        <td class="mathematics__table-data">
                            <a class="link link--row" href="./assets/files/exercises/<?=$exercice['name'].'.'.$exercice['extension']?>" download><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                            <a class="link link--row" href="./assets/files/corrections/<?=$correction['name'].'.'.$correction['extension']?>" download><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="mathematics__content-table">
            <h2 class="mathematics__content-title">Tous les exercices</h2>
            <table class="mathematics__table">
                <thead class="mathematics__table-head">
                <tr class="mathematics__table-row">
                    <th class="mathematics__table-heading name">Nom</th>
                    <th class="mathematics__table-heading thematic">Thématiques</th>
                    <th class="mathematics__table-heading difficulty">Difficulté</th>
                    <th class="mathematics__table-heading duration">Durée</th>
                    <th class="mathematics__table-heading keywords">Mots-clés</th>
                    <th class="mathematics__table-heading files">Fichiers</th>
                </tr>
                </thead>
                <tbody class="mathematics__table-body">
                <?php foreach ($exercises as $exercise) {
                    $file_sorted = get_file_by_exercises($exercise_sorted['exercise_file_id']);
                    $exercice = $file_sorted['exercise'];
                    $correction = $file_sorted['correction'];
                    $keywords = explode(' ', $exercise['keywords']);
                    $duration = $exercise["duration"];
                    if (substr($duration, -2) === ".5") {
                        $duration = str_replace(".5", "h30", $duration);
                    }
                    else {
                        if (floor($duration) == $duration) {
                            $duration .= "h00";
                        }
                    }
                    ?>
                    <tr class="mathematics__table-row">
                        <td class="mathematics__table-data"><?=$exercise['name']?></td>
                        <td class="mathematics__table-data"><?=get_thematic_by_exercises($exercise["thematic_id"])['name'];?>
                        </td>
                        <td class="mathematics__table-data">Niveau <?=$exercise['difficulty']?></td>
                        <td class="mathematics__table-data"><?=$duration??"Aucune"?></td>
                        <td class="mathematics__table-data wordbreak"><?php foreach ($keywords as $keyword) {
                                echo "<p class='keyword'>$keyword</p>";
                            } ?>
                        </td>
                        <td class="mathematics__table-data">
                            <a class="link link--row" href="./assets/files/exercises/<?=$exercice['name'].'.'.$exercice['extension']?>" download><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                            <a class="link link--row" href="./assets/files/corrections/<?=$correction['name'].'.'.$correction['extension']?>" download><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>