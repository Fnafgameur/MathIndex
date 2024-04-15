<?php

    $exercices = [];

    $filtres = [
            "niveau" => "",
            "thematique" => "",
            "mots-cles" => "",
    ];

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST)) {
        $filtres["niveau"] = $_POST["niveau"];
        $filtres["thematique"] = $_POST["thematique"];
        $filtres["mots-cles"] = $_POST["mots-cles"];
        $exercices = get_exercices($filtres);

    } else {
        $exercices = get_exercices();
    }

    $number = $exercices["number"]??0;
    $exercices = $exercices["exercise"];
?>

<div class="recherche">
    <h1 class="recherche__title">Rechercher un exercice</h1>
    <div class="recherche__content">
        <form class="recherche__form" action="#" method="post">
            <div>
                <label for="niveau">Niveau:</label>
                <select name="niveau">
                    <option id="niveau" name="niveau" value="1" <?= $filtres["niveau"] === "1" ? "selected" : "" ?>>Seconde</option>
                    <option id="niveau" name="niveau" value="2" <?= $filtres["niveau"] === "2" ? "selected" : "" ?>>Première</option>
                    <option id="niveau" name="niveau" value="3" <?= $filtres["niveau"] === "3" ? "selected" : "" ?>>Terminale</option>
                </select>
            </div>
            <div>
                <label for="thematique">Thématique:</label>
                <select name="thematique">
                    <option id="thematique"  name="thematique" value="1" <?= $filtres["thematique"] === "1" ? "selected" : "" ?>>Suites</option>
                    <option id="thematique"  name="thematique" value="2" <?= $filtres["thematique"] === "2" ? "selected" : "" ?>>Primitives</option>
                    <option id="thematique"  name="thematique" value="3" <?= $filtres["thematique"] === "3" ? "selected" : "" ?>>Algèbres</option>
                    <option id="thematique"  name="thematique" value="4" <?= $filtres["thematique"] === "4" ? "selected" : "" ?>>Continuités</option>
                    <option id="thematique"  name="thematique" value="5" <?= $filtres["thematique"] === "5" ? "selected" : "" ?>>Matriciel</option>
                    <option id="thematique"  name="thematique" value="6" <?= $filtres["thematique"] === "6" ? "selected" : "" ?>>Géométrie</option>
                    <option id="thematique"  name="thematique" value="7" <?= $filtres["thematique"] === "7" ? "selected" : "" ?>>Trigonométrie</option>
                    <option id="thematique"  name="thematique" value="8" <?= $filtres["thematique"] === "8" ? "selected" : "" ?>>Fonctions et équations</option>
                </select>
            </div>
            <div>
                <label for="mots-cles">Mots clés:</label>
                <input id="mots_cles" name="mots-cles" <?= $filtres["mots-cles"] === "" ? "" : "value=".$filtres["mots-cles"] ?>>
            </div>
            <div>
                <button type="submit">
                    Rechercher
                </button>
            </div>
        </form>
        
        <h2><?= $number; ?> exercices trouvés</h2>

        <table class="recherche__table">
            <thead class="recherche__table-head">
                <tr class="recherche__table-row">
                    <th class="recherche__table-heading nom">Nom</th>
                    <th class="recherche__table-heading difficulte">Difficulté</th>
                    <th class="recherche__table-heading mots-cles">Mots clés</th>
                    <th class="recherche__table-heading duree">Durée</th>
                    <th class="recherche__table-heading fichiers">Fichiers</th>
                </tr>
            </thead>
            <tbody class="recherche__table-body">
            <?php
                foreach ($exercices as $exercice) { ?>

                <tr class="recherche__table-row">
                    <td class="recherche__table-data"><?= $exercice["name"] ?></td>
                    <td class="recherche__table-data"><?= $exercice["difficulty"] ?></td>
                    <td class="recherche__table-data"><?= $exercice["keywords"] ?></td>
                    <td class="recherche__table-data"><?= $exercice["duration"] ?></td>
                    <td class="recherche__table-data">
                        <a href="<?= $exercice["file"]??"" ?>" download="<?= $exercice["file"]??"" ?>">Télécharger</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>