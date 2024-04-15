<div class="research">
    <h1 class="research__title">Rechercher un exercice</h1>
    <div class="research__content">
        <form class="research__form">
            <div>
                <label for="niveau">Niveau:</label>
                <select name="niveau">
                    <option id="niveau" name="niveau">Collège</option>
                    <option id="niveau"  name="niveau">Lycée</option>
                    <option id="niveau"  name="niveau">Supérieur</option>
                </select>
            </div>
            <div>
                <label for="thematique">Thématique:</label>
                <select name="thematique">
                    <option id="thematique"  name="thematique">Suites</option>
                    <option id="thematique"  name="thematique">Algèbre de bol</option>
                    <option id="thematique"  name="thematique">Pytagore</option>
                </select>
            </div>
            <div>
                <label for="mots-cles">Mots clés:</label>
                <input id="mots_cles" name="mots-cles">
            </div>
            <div>
                <button type="submit">
                    Rechercher
                </button>
            </div>
        </form>
        
        <h2>n° exrcices trouvés</h2>

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
            <tbody class="research__table-body">
                <tr class="research__table-row">
                    <td class="research__table-data">test</td>
                    <td class="research__table-data">test</td>
                    <td class="research__table-data">test</td>
                    <td class="research__table-data">test</td>
                    <td class="research__table-data">test</td>
                </tr>
            </tbody>
        </table>
    </div>