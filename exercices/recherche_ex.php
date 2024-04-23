<?php

    $exercices = [];

    $filtres = [
            "niveau" => "",
            "thematique" => "",
            "mots-cles" => "",
    ];

    $searchFilterResult = [
            "result" => true,
            "msg" => "",
    ];

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST)) {
        $filtres["niveau"] = $_POST["niveau"];
        $filtres["thematique"] = $_POST["thematique"];
        $filtres["mots-cles"] = $_POST["mots-cles"];

        $filtres["mots-cles"] = str_replace(["'", '"'], "", $filtres["mots-cles"]);

        $searchFilterResult = is_searching_filter_correct($filtres);

        if ($searchFilterResult["result"]) {
            $exercices = get_exercises(null, $filtres);
        }
        else {
            $exercices = get_exercises();
        }

    } else {
        $exercices = get_exercises();
    }

    $number = $exercices["number"]??0;
    $exercices = $exercices["exercise"];
?>
          
<div class="research">
    <h1 class="research__title">Rechercher un exercice</h1>
    <div class="research__content">
        <form class="research__form" method="post" action="#">

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
                    <option id="thematique"  name="thematique" value="0" <?= $filtres["thematique"] === "0" ? "selected" : "" ?>>Voir tout</option>
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
                <input id="mots_cles" name="mots-cles" value="<?= $filtres["mots-cles"]??"" ?>" type="text">
            </div>
            <div>
                <button type="submit">
                    Rechercher
                </button>
            </div>
        </form>

        <p style="color: red; font-weight: 900; display: <?= $searchFilterResult["result"] ? "none" : "block" ?> "><?= $searchFilterResult["msg"]; ?></p>
        
        <h2><?= $number; ?> exercices trouvés</h2>

        <table class="research__table">
            <thead class="research__table-head">
                <tr class="research__table-row">
                    <th class="research__table-heading nom">Nom</th>
                    <th class="research__table-heading difficulte">Difficulté</th>
                    <th class="research__table-heading mots-cles">Mots clés</th>
                    <th class="research__table-heading duree">Durée</th>
                    <th class="research__table-heading fichiers">Fichiers</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($exercices as $exercice) { ?>

                <tr class="research__table-row">
                    <td class="research__table-data"><?= $exercice["name"] ?></td>
                    <td class="research__table-data"><?= $exercice["difficulty"] ?></td>
                    <td class="research__table-data"><?= $exercice["keywords"] ?></td>
                    <td class="research__table-data"><?= $exercice["duration"] ?></td>
                    <td class="research__table-data">
                        <a href="<?= $exercice["file"]??"" ?>" download="<?= $exercice["file"]??"" ?>">Télécharger</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>