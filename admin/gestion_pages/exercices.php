<?php

    $type = Type::EXERCISE->value;
    $currentAction = "";
    $exercises = [];


    if (isset($_SESSION["formValues"])) {
        if (!array_key_exists("search_ex", $_SESSION["formValues"])) {
            $_SESSION["formValues"] = null;
        }
    }

    $research = $_POST["search"]??$_SESSION["formValues"]["search_ex"]??"";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        if (isset($research)) {
            if (is_null_or_empty($research)["result"]) {
                $exercises = get_all($type,$current_page, $per_page);
            }
            else {
                $exercises = get_by_keywords($type,$current_page, $per_page, $research);
            }
        }

        if (isset($_POST["search"])) {
            $exercises = get_by_keywords($type,$current_page, $per_page, $research);
        }
        else if (isset($_POST["delete"])) {
            $ids = $_POST['delete'];
            $exId = explode(',', $ids)[0];
            $fileId = explode(',', $ids)[1];
            $name = explode(',', $ids)[2];
            $exs = get_file_by_exercises($fileId);
            $exercisePath = $exs["exercise"];
            $correctionPath = $exs["correction"];
            unlink('./assets/files/exercises/'.$exercisePath['name'].'.'.$exercisePath['extension']);
            unlink('./assets/files/corrections/'.$correctionPath['name'].'.'.$correctionPath['extension']);
            $delete = delete_by_id(Type::EXERCISE->value, $exId);
            if ($delete) {
                $didDelete = true;
                $exercises = get_all($type,$current_page, $per_page);
            }
            else {
                echo "<script>alert('Une erreur est survenue lors de la suppression de l\'exercice de $name.');</script>";
                header('Location: index.php?page='.$_GET["page"].'&pagination='.$current_page.'&onglet=exercices');
            }
        }
        else if (isset($_GET["updating"]) && isset($_POST["submit"])) {
            $_SESSION["modify_exercise"] = $_POST["submit"];
        }

        $_SESSION["formValues"]["search_ex"] = $research;
    }
    else {
        if ($research === "") {
            $exercises = get_all($type,$current_page, $per_page);
        }
        else {
            $exercises = get_by_keywords($type,$current_page, $per_page, $research);
        }
    }

    $number = $exercises["number"] ?? 0;
?>

<div class="contributors">

    <h2><?= $currentAction === "adding" ? "Ajouter un exercice" : (($currentAction === "updating") ? "Modifier un exercice" : "Gestion des exercices") ?></h2>

    <?php if ($currentAction === "") { ?>

    <p class="contributors__description">Rechercher un exercice par nom, thématique ou niveau :</p>
    <div class="contributors__action-bar">
        <form action="index.php?page=Administration&onglet=exercices" method="POST" class="contributeurs__form">
            <input type="text" name="search" class="contributeurs__input" placeholder="Rechercher" value="<?= $research ?>">
            <button type="submit">Rechercher</button>
        </form>
        <a href="index.php?page=Soumettre#">
            <button class="contributors__action-add" type="submit">Ajouter
                <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.5 6V0H8.5V6H14.5V8H8.5V14H6.5V8H0.5V6H6.5Z" fill="white"/>
                </svg>
            </button>
        </a>
    </div>
    <table class="contributors__table">
        <thead class="recherche__table-head">
        <tr>
            <th class="name">Nom</th>
            <th class="thematic">Thématique</th>
            <th class="difficulty">Difficulté</th>
            <th class="files">Fichiers</th>
            <th class="actions">Action</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($exercises["values"] as $exercise) {
                $file_sorted = get_file_by_exercises($exercise['exercise_file_id']);
                $exercice = $file_sorted['exercise'];
                $correction = $file_sorted['correction'];
                $originalExerciceName = get_original_name_by_file_id($file_sorted['exercise']['id']);
                $originalCorrectionName = get_original_name_by_file_id($file_sorted['correction']['id']);
                ?>
                    <tr>
                        <td><?= $exercise["name"] ?></td>
                        <td><?= get_thematic_by_exercises($exercise["thematic_id"])["name"] ?></td>
                        <td>Niveau <?= $exercise["difficulty"] ?></td>
                        <td>
                            <a class="link link--row" href="./assets/files/exercises/<?=$exercice['name'].'.'.$exercice['extension']?>" download="<?= $originalExerciceName ?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Exercice</a>
                            <a class="link link--row" href="./assets/files/corrections/<?=$correction['name'].'.'.$correction['extension']?>" download="<?= $originalCorrectionName ?>"><img src="./assets/icons/download_file.svg" alt="logo téléchargement">Corrigé</a>
                        </td>
                        <td>
                            <form action="index.php?page=Soumettre&updating=<?= $exercise["id"] ?>&first" method="post">
                                <input type="hidden" name="update" value="<?= $exercise["id"] ?>">
                                <button type="submit" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                            </form>
                            <form action="" method="post">
                                <input type="hidden" name="delete" value="<?= $exercise["id"] . ',' . $exercise["exercise_file_id"] . ',' . $exercise["name"] ?>">
                                <button type="button" class="contributeurs__button modal__trigger" onclick="sendData(this.parentElement)"><img src="assets/icons/delete_file.svg">Supprimer</button>
                            </form>
                        </td>
                    </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
