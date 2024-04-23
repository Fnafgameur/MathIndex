<?php
    $current_page = get_current_page();
    $nb_exercises = get_exercise_number();
    $per_page = 10;
    $pages = ceil($nb_exercises / $per_page);
    $my_exercises = get_exercises_with_limit($current_page, $per_page, $_SESSION['user']['id']);
?>

<div class="mathematics">
    <h1 class="mathematics__title">Mes exercices</h1>
    <div class="mathematics__content">
        <h2 class="mathematics__content-title">Vous pouvez modifier ou supprimer un de vos exercices.</h2>
        <div class="mathematics__content-table">
            <table class="mathematics__table">
                <thead class="mathematics__table-head">
                <tr class="mathematics__table-row">
                    <th class="mathematics__table-heading name">Nom</th>
                    <th class="mathematics__table-heading thematic">Thématiques</th>
                    <th class="mathematics__table-heading files">Fichiers</th>
                    <th class="mathematics__table-heading actions">Actions</th>
                </tr>
                </thead>
                <tbody class="mathematics__table-body">
                <?php foreach ($my_exercises as $my_exercise) {
                    $file_sorted = get_file_by_exercises($my_exercise['exercise_file_id']);
                    $keywords_sorted = explode(',', $my_exercise['keywords']);
                    ?>
                    <tr class="mathematics__table-row">
                        <td class="mathematics__table-data"><?=$my_exercise['name']?></td>
                        <td class="mathematics__table-data"><?=get_thematic_by_exercises($my_exercise["thematic_id"])['name'];?></td>
                        <td class="mathematics__table-data">
                            <a class="link link--row" href="" download="<?=$file_sorted['name'].'.'.$file_sorted['extension']?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                            <a class="link link--row" href="" download="<?=str_contains(strtolower($file_sorted['name']),'_corrigé')?$file_sorted['name'].'_corrigé':$file_sorted['name'].$file_sorted['extension'];?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                        </td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="id" value="<?=$my_exercise['id']?>">
                                <input type="submit" class="btn link--row" value="Modifier">
                            </form>
                            <form action="">
                                <input type="hidden" name="id" value="<?=$my_exercise['id']?>">
                                <input type="submit" class="btn link--row" value="Supprimer">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="mathematics__pagination">
            <a class="link link__pagination <?=($current_page) === 1 ? 'link__arrow': null;?>" href="index.php?page=Mes+exercices&pagination=<?=($current_page - 1) < 1 ? 1 : $current_page - 1;?>"><img src="./assets/icons/left_arrow.svg" alt="fleche gauche"></a>
            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                <a class="link link__pagination <?=($current_page) === $i ? 'link__number' : null;?>" href="index.php?page=Mes+exercices&pagination=<?=$i;?>"><?=$i?></a>
            <?php } ?>
            <a class="link link__pagination <?=($current_page) >= $pages ? 'link__arrow': null;?>" href="index.php?page=Mes+exercices&pagination=<?=($current_page + 1) > $pages ? $pages : $current_page + 1;?>"><img src="./assets/icons/right_arrow.svg" alt="fleche gauche"></a>
        </div>
    </div>
</div>

