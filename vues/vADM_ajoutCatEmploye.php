<div class="contenu">
    <div class="contenu_interne">
        <h2>Ajouter un nouveau type d'employé : </h2>
        <form method="POST" action="index.php?uc=gererCatEmploye&action=validerCreationCatEmploye">
            <label for="idCatEmploye">ID* :
                <input type="text" name="idCatEmploye" id="idCatEmploye">
            </label>
            <label for="libelleCatEmploye">Libellé catégorie* :
                <input type="text" name="libelleCatEmploye" id="libelleCatEmploye">
            </label>
            <button type="submit" value="Valider" name="btn_valider">Valider</button>
            <a href="index.php?uc=gererCatEmploye&action=voirCatEmploye">
                <button type="button" value="Annuler" name="btn_annuler">Annuler</button>
            </a>
        </form>    
    </div>
</div>
