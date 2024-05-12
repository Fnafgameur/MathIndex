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
    echo "<pre>".var_dump($origins)."</pre>";

    $errors=[
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
        "fichier_exercice" => "",
        "fichier_correction" => "",
    ];

    $displays = [
        "info" => "block",
        "source" => "none",
        "file" => "none",
        'name' => "none",
        'classroom' => "none",
        'thematic' => "none",
        'chapter' => "none",
        'keywords' => "none",
        'difficulty' => "none",
        'duration' => "none",
        "origin" => "none",
        'origin_name' => "none",
        'origin_information' => "none",
        "fichier_exercice" => "none",
        "fichier_correction" => "none",
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
        "fichier_exercice" => "Selectionné un fichier à télécharger",
        "fichier_correction" => "Selectionné un fichier à télécharger",
    ];

    if (!(empty($_POST))){
        
        

        foreach($_POST as $key => $value){
            if($key === "origin" or
            $key === "classroom" or
            $key === "thematic" or
            $key === "difficulty"){
                $values[$key]= ("<option value='".$value."'>".$value."</option>");
                if ($key === "origin" and (!name_in_array($value,$origins))){
                    $errors[$key] = "Erreur, cette valeur est incorrecte";
                    $displays[$key] = "block";
                } else if ($key === "classroom" and (!name_in_array($value,$classrooms))){
                    $errors[$key] = "Erreur, cette valeur est incorrecte";
                    $displays[$key] = "block";
                } else if ($key === "thematic" and (!name_in_array($value,$thematics))){
                    $errors[$key] = "Erreur, cette valeur est incorrecte";
                    $displays[$key] = "block";
                } else if ($key === "difficulty" and (!in_array($value,$difficulty))){
                    $errors[$key] = "Erreur, cette valeur est incorrecte";
                    $displays[$key] = "block";
                }
            }
            else if($key === "origin_information")
            {
                $values[$key] = $value;
                if(!is_under_255($_POST[$key])){
                    var_dump($key);
                    $errors[$key] = "Ce champs ne peut pas dépassé les 255 caractères";
                    $displays[$key] = "block";
                }

            }
            else if($key === "file"){

            }
            else if(isset($value))
            {
                $values[$key] = "value='".$value."'";
                if(!is_under_255($_POST[$key])){
                    $errors[$key] = "Ce champs ne peut pas dépassé les 255 caractères";
                    $displays[$key] = "block";
                }
            }
        }

        foreach($_FILES as $key => $file){
            if ($key === "fichier_exercice" or
            $key === "fichier_correction"){
                if (!empty($_FILES[$key])){
                    $values[$key] = $_FILES[$key]["name"];
                    if ($_FILES[$key]["size"] > 2000000){
                        $errors[$key] = "Un fichié à une taille de 2mo maximum";
                        $displays[$key] = "block";
                    }
                    else if ($_FILES[$key]["size"] <= 0){
                        $errors[$key] = "l'ajout de ce fichier est obligatoire";
                        $displays[$key] = "block";
                    }
                }
            }
        }

        if ($errores['name'] and 
        $errores['classroom'] and 
        $errores['thematic'] and 
        $errores['chapter'] and 
        $errores['keywords'] and 
        $errores['difficulty'] and 
        $errores['duration'] and 
        $errores["origin"] and 
        $errores['origin_name'] and 
        $errores['origin_information'] and 
        $errores["fichier_exercice"] and 
        $errores["fichier_correction"]){
            /* envoie dans la bdd à faire ici*/
            
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
                        <p class="errormsg" class="display: <?= $displays["name"] .'">'.$errors["name"] ?> </p>
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
                        </select>
                        <p class="errormsg" class="display: <?= $displays["classroom"] ?>"><?= $errors["classroom"] ?></p><br>
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
                        </select>
                        <p class="errormsg" class="display: <?= $displays["thematic"] ?>"><?= $errors["thematic"] ?></p><br>
                        <label for="chapter">Chapitre du cours:</label><br>
                        <input id="chapter"  name="chapter" <?= $values["chapter"]?>>
                        <p class="errormsg" class="display: <?= $displays["chapter"] ?>"><?= $errors["chapter"] ?></p>
                    </div>
                    <div>
                        <label for="keywords">Mots clés</label><br>
                        <input id="keywords"  name="keywords" <?= $values["keywords"]?>>
                        <p class="errormsg" class="display: <?= $displays["keywords"] ?>"><?= $errors["keywords"] ?></p><br>
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
                        </select>
                        <p class="errormsg" class="display: <?= $displays["difficulty"] ?>"><?= $errors["difficulty"] ?></p><br>
                        <label for="duration">Durée (en heure):</label><br>
                        <input id="duration"  name="duration" <?= $values["duration"]?>>
                        <p class="errormsg" class="display: <?= $displays["duration"] ?>"><?= $errors["duration"] ?></p>
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
                </select>
                <p class="errormsg" class="display: <?= $displays["origin"] ?>"><?= $errors["origin"] ?></p><br>
                <label for="origin_name">Nom de la source/lien du site: *</label>
                <input id="origin_name"  name="origin_name" <?= $values["origin_name"]?>>
                <p class="errormsg" class="display: <?= $displays["origin_name"] ?>"><?= $errors["origin_name"] ?></p>
                <label for="origin_information">Information complémentaires:</label>
                <textarea id="origin_information"  name="origin_information" ><?= $values["origin_information"] ?></textarea>
                <p class="errormsg" class="display: <?= $displays["origin_information"] ?>"><?= $errors["origin_information"] ?></p>
                <button type="button" id="next_file">Continuer</button>
            </div>

            <div class="submit__file" id="submit__file" style="display:<?= $displays["file"] ?>">
                <h2>Fichiers</h2>
                <label for="fichier_exercice">fichier exercice: (pdf, docx)*<br>
                    <div>
                        <p id="fichier_exercice_choisit"><?= $values["fichier_exercice"] ?></p>
                        <img src="./assets/icons/upload_cloud.svg">
                    </div>
                    <input type="file" accept=".docx, .pdf, .txt" id="fichier_exercice"  name="fichier_exercice" hidden/>
                </label>
                <p class="errormsg" class="display: <?= $displays["fichier_exercice"] ?>"><?= $errors["fichier_exercice"] ?></p>
                <label for="fichier_correction">fichier correction: (pdf, docx)*<br>
                    <div>
                        <p id="fichier_correction_choisit"><?= $values["fichier_correction"] ?></p>
                        <img src="./assets/icons/upload_cloud.svg">
                    </div>
                    <input type="file" accept=".docx, .pdf, .txt" id="fichier_correction"  name="fichier_correction"  hidden/>
                </label>
                <p class="errormsg" class="display: <?= $displays["fichier_correction"] ?>"><?= $errors["fichier_correction"] ?></p>
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>