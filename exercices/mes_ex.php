<?php
    $current_page = get_current_page();
    $per_page = 10;
    $my_exercises = get_exercises_with_limit($current_page, $per_page, $_SESSION['user']['id']);
    $nb_exercises = $my_exercises['number'];
    $my_exercises = $my_exercises['exercises'];
    $pages = ceil($nb_exercises / $per_page);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) ) {
        $id_exercise = $_POST['delete'];
        delete_by_id(Type::EXERCISE->value, $id_exercise);
        header('Location: index.php?page=Mes+exercices&pagination='.$current_page);
    }
    if (empty($my_exercises)){
        if ($current_page > 1) {
            header('Location: index.php?page=Mes+exercices&pagination='.($current_page - 1));
        } else if ($current_page !== 1) {
            header('Location: index.php?page=Mes+exercices');
        }
    }
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
                        <th class="exercise__table-heading" style="width: 10%">Nom</th>
                        <th class="exercise__table-heading" style="width: 10%">Thématiques</th>
                        <th class="exercise__table-heading" style="width: 10%">Fichiers</th>
                        <th class="exercise__table-heading" style="width: 5%">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="exercise__table-body">
                    <?php foreach ($my_exercises as $my_exercise) {
                        $file_sorted = get_file_by_exercises($my_exercise['exercise_file_id']);
                        $exerciceFile = $file_sorted['exercise'];
                        $correctionFile = $file_sorted['correction'];
                        ?>
                        <tr class="exercise__table-row">
                            <td class="exercise__table-data"><?=$my_exercise['name']?></td>
                            <td class="exercise__table-data"><?=get_thematic_by_exercises($my_exercise["thematic_id"])['name'];?></td>
                            <td class="exercise__table-data exercise__actions">
                                <a class="link link--row" href="./assets/files/exercises/<?=$exerciceFile['name'].'.'.$exerciceFile['extension']?>" download><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                                <a class="link link--row" href="./assets/files/corrections/<?=$correctionFile['name'].'.'.$correctionFile['extension']?>" download><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                            </td>
                            <td class="exercise__table-data exercise__form">
                                <form action="index.php?page<?=Page::SOUMETTRE->value?>" method="post" class="exercise_modify_form">
                                    <img src="./assets/icons/edit_file.svg" alt="logo modification">
                                    <input type="hidden" name="id" value="<?=$my_exercise['id']?>">
                                    <input type="submit" class="btn btn--bgwhite btn--lightgrey" value="Modifier">
                                </form>
                                <form action="" method="post" class="exercise_delete_form">
                                    <img src="./assets/icons/delete_file.svg" alt="logo suppression">
                                    <input type="hidden" name="delete" value="<?=$my_exercise['id']?>">
                                    <button type="button" class="btn btn--bgwhite btn--lightgrey modal__trigger" onclick="sendData(this.parentElement)">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>



