<?php
    $uploads_exo = './assets/files/exercises/';
    $uploads_cor = './assets/files/corrections/';
    /* mis en commentaire le temps de faire les verifs
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
    */
    
    $classrooms = get_classrooms_names();

    $thematics = get_thematics_names();

    $difficulty = [];
    for ($i = 0; $i <= 20; $i++) {
        $difficulty[$i] = $i;
    }

    $origins = get_origins_names();

    $errors=[
        'name' => "",
        'classroom' => "",
        'thematic' => "",
        'chapter' => "",
        'keywords' => "",
        'difficulty' => "",
        'duration' => "",
        'origin_name' => "",
        'origin_information' => "",
    ];

    $displays = [
        "info" => "block",
        "source" => "none",
        "file" => "none",
    ];

    $values=[
        'name' => "",
        'classroom' => "",
        'thematic' => "",
        'chapter' => "",
        'keywords' => "",
        'difficulty' => "",
        'duration' => "",
        "origin" => "",
        'origin_name' => "",
        'origin_information' => "",
    ];

    if (isset($_POST['name'])){
    
        var_dump($_POST);

        foreach($_POST as $key => $value){
            if($key === "origin" or
            $key === "classroom" or
            $key === "thematic" or
            $key === "difficulty"){
                $values[$key]= ("<option value='".$value."'>".$value."</option>");
            }
            else if($key === "origin_information"){
                $values[$key] = $value;

            }
            else if(isset($value)){
                $values[$key] = "value='".$value."'";
            }
        }

        
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
            <div class="submit__information" id="submit__information" style="display:<?= $displays["info"] ?>">
                <h2>Information générales</h2>
                <div class="submit__information__flex">

                    <div>
                        <label for="name">Nom de l'exercice: *</label><br>
                        <input id="name" name="name" <?= $values["name"]?>><br>
                        <label for="classroom">Classe: *</label><br>
                        <select name="classroom" id="classroom">
                            <?php
                                echo $values["classroom"];
                                foreach($classrooms as $class){
                                    echo'
                                    <option value='.$class["name"].'>'.$class["name"].'</option>
                                    ';
                                }
                            ?>
                        </select><br>
                        <label for="thematic">Thématique: *</label><br>
                        <select name="thematic"id="thematic">
                            <?php
                                echo $values["thematic"];
                                foreach($thematics as $them){
                                    echo'
                                    <option value='.$them["name"].'>'.$them["name"].'</option>
                                    ';
                                }
                            ?>
                        </select><br>
                        <label for="chapter">Chapitre du cours:</label><br>
                        <input id="chapter"  name="chapter" <?= $values["chapter"]?>>
                    </div>
                    <div>
                        <label for="keywords">Mots clés</label><br>
                        <input id="keywords"  name="keywords" <?= $values["keywords"]?>><br>
                        <label for="difficulty">Difficulté: *</label><br>
                        <select name="difficulty" id="difficulty" >
                            <?php
                                echo $values["difficulty"];
                                foreach($difficulty as $diff){
                                    echo'
                                    <option value='.$diff.'>'.$diff.'</option>
                                    ';
                                }
                            ?>
                        </select><br>
                        <label for="duration">Durée (en heure):</label><br>
                        <input id="duration"  name="duration" <?= $values["duration"]?>>
                    </div>
                </div>
                <button type="button" id="next_source">Continuer</button>
            </div>

            <div class="submit__source" id="submit__source"  style="display:<?= $displays["source"] ?>">
                <h2>Sources</h2>
                <label for="origin">Origine: *</label>
                <select name="origin" id="origin">
                    <?php
                        echo $values["origin"];
                        foreach($origins as $origin){
                            echo'
                            <option value='.$origin["name"].'>'.$origin["name"].'</option>
                            ';
                        }
                    ?>
                </select><br>
                <label for="origin_name">Nom de la source/lien du site: *</label>
                <input id="origin_name"  name="origin_name" <?= $values["origin_name"]?>>
                <label for="origin_information">Information complémentaires:</label>
                <textarea id="origin_information"  name="origin_information" ><?= $values["origin_information"] ?></textarea>
                <button type="button" id="next_file">Continuer</button>
            </div>

            <div class="submit__file" id="submit__file" style="display:<?= $displays["file"] ?>">
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