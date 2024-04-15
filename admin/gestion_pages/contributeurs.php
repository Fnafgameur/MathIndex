
<div class="contributors">
    <h2 class="contributors__title">Gestion des contributeurs</h2>
    <p class="contributors__description">Rechercher un contributeur par nom, prénom ou email :</p>
    <div class="contributors__action-bar">
        <form action="#" method="GET" class="contributeurs__form">
            <input type="text" name="search" id="search" class="contributeurs__input" placeholder="Rechercher">
            <button type="submit">Rechercher</button>
        </form>
        <a>
            <button type="submit">Ajouter +</button>
        </a>
    </div>
    <table class="contributors__table">
        <thead class="recherche__table-head">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
                <th>Email</th>
                <th class="action">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>DOE</td>
                <td>John</td>
                <td>Enseignant</td>
                <td>mail@mail.com</td>
                <td>
                    <button type="button" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                    <button type="button" class="contributeurs__button"><img src="assets/icons/delete_file.svg">Supprimer</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>