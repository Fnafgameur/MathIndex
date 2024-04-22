<div class="submit">
    <h1 class="submit__title">Soumettre un exercice</h1>
    <div class="administration__head-bar">
        <button type="button" class="administration__buttons-button">Contributeurs</button>
    
        <button type="button" class="administration__buttons-button">Exercices</button>
    
        <button type="button" class="administration__buttons-button">Matières</button>
    </div>
    <div class="submit__content">
        <form action="#" method="post">
            <div class="submit__information">
                <h2>Information générales</h2>
                <div class="subimit__information__flex">

                    <div>
                        <label for="nom">Nom de l'exercice*</label>
                        <input id="nom" name="nom">
                        <label for="classe">Classe*</label>
                        <select name="classe">
                            <option id="classe" name="classe">Collège</option>
                            <option id="classe"  name="classe">Lycée</option>
                            <option id="classe"  name="classe">Supérieur</option>
                        </select>
                        <label for="thematique">Thématique</label>
                        <select name="thematique">
                            <option id="thematique" name="thematique">Collège</option>
                            <option id="thematique"  name="thematique">Lycée</option>
                            <option id="thematique"  name="thematique">Supérieur</option>
                        </select>
                        <label for="chapitre">Chapitre du cours</label>
                        <input id="chapitre"  name="chapitre">
                    </div>
                    <div>
                        <label for="mots_cles">Mots clés</label>
                        <input id="chapitre"  name="chapitre">
                        <label for="difficulte">Difficulté *</label>
                        <select name="difficulte">
                            <option id="difficulte" name="difficulte">Collège</option>
                            <option id="difficulte"  name="difficulte">Nigger</option>
                            <option id="difficulte"  name="difficulte">Supérieur</option>
                        </select>
                        <label for="duree">Durée (en heure)</label>
                        <input id="duree"  name="duree">
                    </div>
                </div>
                <button>Continuer</button>
            </div>
            <div class="submit__source">
                <h2>Sources</h2>
                <label for="origine">Origine: *</label>
                <select name="origine">
                    <option id="origine" name="origine">Collège</option>
                    <option id="origine"  name="origine">Nigger</option>
                    <option id="origine"  name="origine">Supérieur</option>
                </select>
                <label for="site">Nom de la source/lien du site: *</label>
                <input id="site"  name="site">
                <label for="info_comp">Iformation complémentaires:</label>
                <textarea id="info_comp"  name="info_comp"></textarea>
                <button>Continuer</button>
            </div>
            <div class="submit__file">
                <h2>Fichiers</h2>
                <label for="fichier_exercice">fichier exercice: (pdf, docx)*</label>
                <input type="file" accept="document/docx, document/pdf, document/txt" id="fichier_exercice"  name="fichier_exercice">
                <label for="ficher_correction">fichier correction: (pdf, docx)*</label>
                <input type="file" accept="document/docx, document/pdf, document/txt" id="ficher_correction"  name="ficher_correction">
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>