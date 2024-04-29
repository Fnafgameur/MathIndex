<?php

    $currentAction = "";
    $exercises = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST)) {
        if (isset($_POST["search"])) {
            $exercises = get_exercises(null, $_POST["search"]);
        }
        else if (isset($_POST["delete"])) {
            $id = explode(",", $_POST["delete"])[0];
            $name = explode(",", $_POST["delete"])[1];
            $delete = delete_exercise($id);
            if ($delete) {
                echo "<script>alert('L\'exercice de $name a bien été supprimé.');</script>";
            }
            else {
                echo "<script>alert('Une erreur est survenue lors de la suppression de l\'exercice de $name.');</script>";
            }
        }
    }
    else {
        $exercises = get_exercises();
    }


?>

<div class="contributors">

    <h2><?= $currentAction === "adding" ? "Ajouter un exercice" : (($currentAction === "updating") ? "Modifier un exercice" : "Gestion des exercices") ?></h2>

    <?php if ($currentAction === "") { ?>

    <p class="contributors__description">Rechercher un contributeur par nom, prénom ou email :</p>
    <div class="contributors__action-bar">
        <form action="index.php?page=Administration" method="POST" class="contributeurs__form">
            <input type="text" name="search" class="contributeurs__input" placeholder="Rechercher">
            <button type="submit">Rechercher</button>
        </form>
        <a href="index.php?page=Administration&adding">
            <button type="submit">Ajouter +</button>
        </a>
    </div>
    <table class="contributors__table">
        <thead class="recherche__table-head">
        <tr>
            <th class="name">Nom</th>
            <th class="thematic">Thématique</th>
            <th class="difficulty">Niveau</th>
            <th class="files">Fichiers</th>
            <th class="actions">Action</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($exercises["exercise"] as $exercise) {
                $file = get_file_by_exercises($exercise['exercise_file_id']);
                ?>
                    <tr>
                        <td><?= $exercise["name"] ?></td>
                        <td><?= get_thematic_by_exercises($exercise["thematic_id"])["name"] ?></td>
                        <td><?= $exercise["difficulty"] ?></td>
                        <td>
                            <a class="link link--row" href="<?= $file ?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                            <a class="link link--row" href="<?= $file ?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                        </td>
                        <td>
                            <form action="index.php?page=Administration&updating=<?= $exercise["id"] ?>&first" method="post">
                                <input type="hidden" name="update" value="<?= $exercise["id"] ?>">
                                <button type="submit" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                            </form>
                            <form action="#" method="post">
                                <input type="hidden" name="delete" value="<?= $exercise["id"] . ',' . $exercise["name"] ?>">
                                <button type="submit" class="contributeurs__button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"><img src="assets/icons/delete_file.svg">Supprimer</button>
                            </form>
                        </td>
                    </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</div>
