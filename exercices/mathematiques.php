<?php
    $exercises_sorted = get_exercises_sorted();
    $current_page = get_current_page();
    $nb_exercises = get_exercise_number();
    $per_page = 5;
    $pages = ceil($nb_exercises / $per_page);
    $exercises = get_exercises_with_limit($current_page, $per_page);
?>
<div class="mathematics">
    <h1 class="mathematics__title">Exercices</h1>
    <div class="mathematics__content">
        <h2 class="mathematics__content-title">Nouveautés</h2>
        <div>
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
                    $result_array = explode(',', $exercise_sorted['keywords']);
                    ?>
                    <tr class="mathematics__table-row">
                        <td class="mathematics__table-data"><?=$exercise_sorted['name']?></td>
                        <td class="mathematics__table-data"><?=get_thematic_by_exercises($exercise_sorted["thematic_id"])['name'];?>
                        </td>
                        <td class="mathematics__table-data"><?=$exercise_sorted['difficulty']?></td>
                        <td class="mathematics__table-data"><?=$exercise_sorted['duration']??"Aucun"?></td>
                        <td class="mathematics__table-data wordbreak"><?php foreach ($result_array as $result) {
                                echo "<p class='keyword'>$result</p>";
                            } ?>
                        </td>
                        <td class="mathematics__table-data">
                            <a class="link link--row" href="" download="<?=$file_sorted['name'].'.'.$file_sorted['extension']?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                            <a class="link link--row" href="" download="<?=str_contains(strtolower($file_sorted['name']),'_corrigé')?$file_sorted['name'].'_corrigé':$file_sorted['name'].$file_sorted['extension'];?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
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
                    $file = get_file_by_exercises($exercise['exercise_file_id']);
                    ?>
                    <tr class="mathematics__table-row">
                        <td class="mathematics__table-data"><?=$exercise['name']?></td>
                        <td class="mathematics__table-data"><?=get_thematic_by_exercises($exercise["thematic_id"])['name'];?>
                        </td>
                        <td class="mathematics__table-data"><?=$exercise['difficulty']?></td>
                        <td class="mathematics__table-data"><?=$exercise['duration']??"Aucun"?></td>
                        <td class="mathematics__table-data wordbreak"><?php foreach ($result_array as $result) {
                                echo "<p class='keyword'>$result</p>";
                            } ?>
                        </td>
                        <td class="mathematics__table-data">
                            <a class="link link--row" href="" download="<?=$file_sorted['name'].'.'.$file_sorted['extension']?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                            <a class="link link--row" href="" download="<?=str_contains(strtolower($file_sorted['name']),'_corrigé')?$file_sorted['name'].'_corrigé':$file_sorted['name'].$file_sorted['extension'];?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="mathematics__pagination">
                <a class="link link--pagination" href="index.php?page=mathematiques&pagination=<?=($current_page - 1) < 1 ? 1 : $current_page - 1;?>"><</a>
                <?php for ($i = 1; $i <= $pages; $i++) { ?>
                    <a class="link link--pagination" href="index.php?page=mathematiques&pagination=<?=$i;?>"><?=$i?></a>
                <?php } ?>
                <a class="link link--pagination" href="index.php?page=mathematiques&pagination=<?=($current_page + 1) > $pages ? $pages : $current_page + 1;?>">></a>
            </div>
        </div>
    </div>
</div>