<?php
    $current_page = get_current_page();
    $nb_exercises = get_exercise_number();
    $per_page = 10;
    $pages = ceil($nb_exercises / $per_page);
    $my_exercises = get_exercises_with_limit($current_page, $per_page, $_SESSION['user']['id']);
?>

<div class="exercise">
    <h1 class="exercise__title">Mes exercices</h1>
    <div class="exercise__content">
        <h2 class="exercise__content-title">Vous pouvez modifier ou supprimer un de vos exercices.</h2>
        <?php if(empty($my_exercises)) { ?>
            <p class="exercise__content-text">Vous n'avez pas encore ajouté d'exercice.</p>
        <?php } else {?>
            <div class="exercise__content-table">
                <table class="exercise__table">
                    <thead class="exercise__table-head">
                    <tr class="exercise__table-row">
                        <th class="exercise__table-heading name">Nom</th>
                        <th class="exercise__table-heading thematic">Thématiques</th>
                        <th class="exercise__table-heading files">Fichiers</th>
                        <th class="exercise__table-heading actions">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="exercise__table-body">
                    <?php foreach ($my_exercises as $my_exercise) {
                        $file_sorted = get_file_by_exercises($my_exercise['exercise_file_id']);
                        $keywords_sorted = explode(',', $my_exercise['keywords']);
                        ?>
                        <tr class="exercise__table-row">
                            <td class="exercise__table-data"><?=$my_exercise['name']?></td>
                            <td class="exercise__table-data"><?=get_thematic_by_exercises($my_exercise["thematic_id"])['name'];?></td>
                            <td class="exercise__table-data exercise__actions">
                                <a class="link link--row" href="" download="<?=$file_sorted['name'].'.'.$file_sorted['extension']?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                                <a class="link link--row" href="" download="<?=str_contains(strtolower($file_sorted['name']),'_corrigé')?$file_sorted['name'].'_corrigé':$file_sorted['name'].$file_sorted['extension'];?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                            </td>
                            <td class="exercise__table-data exercise__form">
                                <form action="#" method="POST">
                                    <img src="./assets/icons/edit_file.svg" alt="logo modification">
                                    <input type="hidden" name="id" value="<?=$my_exercise['id']?>">
                                    <input type="submit" class="btn btn--lb" value="Modifier">
                                </form>
                                <form action="" method="POST">
                                    <img src="./assets/icons/delete_file.svg" alt="logo suppression">
                                    <input type="hidden" name="id" value="<?=$my_exercise['id']?>">
                                    <button type="button" class="btn btn--lb modal__trigger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>



