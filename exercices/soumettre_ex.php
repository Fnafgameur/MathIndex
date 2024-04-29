<?php
    $uploads_exo = './assets/files/exercises/';
    $uploads_cor = './assets/files/corrections/';
    $alter = 0;
    foreach ($_FILES as $file) {
        if ($file["error"] === UPLOAD_ERR_OK) {
            $tmp_name = $file["tmp_name"];
            $name = basename($file["name"]);
            if (($alter%2) === 0){
                $uploads_dir = $uploads_exo ;
                echo "exo";
            } else {
                $uploads_dir = $uploads_cor;
                echo "cor";
            }
            $destination = $uploads_dir . $name ;
            var_dump($destination);
            if (move_uploaded_file($tmp_name, $destination )) {
                echo "File uploaded successfully.";
                $fileContents = file_get_contents($destination);
            } else {
                echo "Failed to move the file.";
            }
        }
        $alter += 1;
    }
    
?>

<div class="submit">
    <h1 class="submit__title">Soumettre un exercice</h1>
    <div class="submit__head-bar">
        <button type="button" class="" id="btn_information">Informations</button>
    
        <button type="button" class=""  id="btn_source">Source</button>
    
        <button type="button" class="" id="btn_file">Fichiers</button>
    </div>
    <div class="submit__content">
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="submit__information" id="submit__information">
                <h2>Information générales</h2>
                <div class="submit__information__flex">

                    <div>
                        <label for="nom">Nom de l'exercice: *</label><br>
                        <input id="nom" name="nom"><br>
                        <label for="classe">Classe: *</label><br>
                        <select name="classe" id="classe">
                            <option value="classe" style="padding:12rem">Collège</option>
                            <option value="classe">Lycée</option>
                            <option value="classe">Supérieur</option>
                        </select><br>
                        <label for="thematique">Thématique: *</label><br>
                        <select name="thematique"id="thematique">
                            <option value="thematique">Listes</option>
                            <option value="thematique">Pythagore</option>
                            <option value="thematique">Thales</option>
                        </select><br>
                        <label for="chapitre">Chapitre du cours:</label><br>
                        <input id="chapitre"  name="chapitre">
                    </div>
                    <div>
                        <label for="mots_cles">Mots clés</label><br>
                        <input id="mots_cles"  name="mots_cles"><br>
                        <label for="difficulte">Difficulté: *</label><br>
                        <select name="difficulte" id="difficulte">
                            <option value="difficulte">eazy</option>
                            <option value="difficulte">hard</option>
                            <option value="difficulte">ultra hard</option>
                        </select><br>
                        <label for="duree">Durée (en heure):</label><br>
                        <input id="duree"  name="duree">
                    </div>
                </div>
                <button type="button" id="next_source">Continuer</button>
            </div>

            <div class="submit__source" id="submit__source"  style="display: none;">
                <h2>Sources</h2>
                <label for="origine">Origine: *</label>
                <select name="origine" id="origine">
                    <option value="origine">Collège</option>
                    <option  value="origine">Nigger</option>
                    <option  value="origine">Supérieur</option>
                </select><br>
                <label for="site">Nom de la source/lien du site: *</label>
                <input id="site"  name="site">
                <label for="info_comp">Information complémentaires:</label>
                <textarea id="info_comp"  name="info_comp"></textarea>
                <button type="button" id="next_file">Continuer</button>
            </div>

            <div class="submit__file" id="submit__file" style="display: none;">
                <h2>Fichiers</h2>
                <label for="fichier_exercice">fichier exercice: (pdf, docx)*<br>
                    <div>
                        <p id="fichier_exercice_choisit">Selectionné un fichier à télécharger</p>
                        <img src="./assets/icons/upload_cloud.svg">
                    </div>
                    <input type="file" accept=".docx, .pdf, .txt" id="fichier_exercice"  name="fichier_exercice" hidden/>
                </label>
                <label for="ficher_correction">fichier correction: (pdf, docx)*<br>
                    <div>
                        <p id="fichier_correction_choisit">Selectionné un fichier à télécharger</p>
                        <img src="./assets/icons/upload_cloud.svg">
                    </div>
                    <input type="file" accept=".docx, .pdf, .txt" id="ficher_correction"  name="ficher_correction" hidden/>
                </label>
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>