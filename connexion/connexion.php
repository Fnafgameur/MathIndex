<div class="connexion">
    <h1 class="connexion__title">Connexion</h1>
    <div class="connexion__content">
        <p class="connexion__description">Cet espace est réservé aux enseignants du lycée Saint-Vincent - Senlis. Si vous n’avez pas encore de compte, veuillez effectuer votre
demande directement en envoyant un email à <a href="mailto:contact@lyceestvincent.net">contact@lyceestvincent.net</a>.
        </p>
        <form action="connexion.php" method="post" class="connexion__form">
            <label for="email" class="connexion__label">Email :</label>
            <input type="text" name="email" id="email" class="connexion__input" placeholder="Saisissez votre adresse email">
            <label for="password" class="connexion__label">Mot de passe :</label>
            <input type="password" name="password" id="password" class="connexion__input" placeholder="Saisissez votre mot de passe">
            <div class="connexion__buttons">
                <button type="submit" class="connexion__button connexion__button-connexion">Connexion</button>
                <button type="button" class="connexion__button connexion__button-pwforget">Mot de passe oublié ?</button>
            </div>
        </form>
    </div>
</div>