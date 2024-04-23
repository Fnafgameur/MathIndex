<div class="submit">
    <h1 class="submit__title">Soumettre un exercice</h1>
    <div class="submit__head-bar">
        <button type="button" class="" id="btn_information">Informations</button>
    
        <button type="button" class=""  id="btn_source">Source</button>
    
        <button type="button" class="" id="btn_file">Fichiers</button>
    </div>
    <div class="submit__content">
        <form action="#" method="post">
            <div class="submit__information" id="submit__information">
                <h2>Information générales</h2>
                <div class="submit__information__flex" id="submit__information">

                    <div>
                        <label for="nom">Nom de l'exercice: *</label><br>
                        <input id="nom" name="nom"><br>
                        <label for="classe">Classe: *</label><br>
                        <select name="classe">
                            <option id="classe" name="classe">Collège</option>
                            <option id="classe"  name="classe">Lycée</option>
                            <option id="classe"  name="classe">Supérieur</option>
                        </select><br>
                        <label for="thematique">Thématique: *</label><br>
                        <select name="thematique">
                            <option id="thematique" name="thematique">Collège</option>
                            <option id="thematique"  name="thematique">Lycée</option>
                            <option id="thematique"  name="thematique">Supérieur</option>
                        </select><br>
                        <label for="chapitre">Chapitre du cours:</label><br>
                        <input id="chapitre"  name="chapitre">
                    </div>
                    <div>
                        <label for="mots_cles">Mots clés</label><br>
                        <input id="chapitre"  name="chapitre"><br>
                        <label for="difficulte">Difficulté: *</label><br>
                        <select name="difficulte">
                            <option id="difficulte" name="difficulte">Collège</option>
                            <option id="difficulte"  name="difficulte">Nigger</option>
                            <option id="difficulte"  name="difficulte">Supérieur</option>
                        </select><br>
                        <label for="duree">Durée (en heure):</label><br>
                        <input id="duree"  name="duree">
                    </div>
                </div>
                <button type="button" id="next_source">Continuer</button>
            </div>

            <div class="submit__source" id="submit__source">
                <h2>Sources</h2>
                <label for="origine">Origine: *</label>
                <select name="origine">
                    <option id="origine" name="origine">Collège</option>
                    <option id="origine"  name="origine">Nigger</option>
                    <option id="origine"  name="origine">Supérieur</option>
                </select><br>
                <label for="site">Nom de la source/lien du site: *</label>
                <input id="site"  name="site">
                <label for="info_comp">Information complémentaires:</label>
                <textarea id="info_comp"  name="info_comp"></textarea>
                <button type="button" id="next_file">Continuer</button>
            </div>

            <div class="submit__file" id="submit__file">
                <h2>Fichiers</h2>
                <label for="fichier_exercice">fichier exercice: (pdf, docx)*<br>
                    <div>
                        <p>Selectionné un fichier à télécharger</p>
                        <img src="./assets/icons/upload_cloud.svg">
                    </div>
                    <input type="file" accept="document/docx, document/pdf, document/txt" id="fichier_exercice"  name="fichier_exercice">
                </label>
                <label for="ficher_correction">fichier correction: (pdf, docx)*<br>
                    <div>
                        <p>Selectionné un fichier à télécharger</p>
                        <img src="./assets/icons/upload_cloud.svg">
                    </div>
                    <input type="file" accept="document/docx, document/pdf, document/txt" id="ficher_correction"  name="ficher_correction">
                </label>
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>