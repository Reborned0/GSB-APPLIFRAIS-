<div class="contenu">
    <div class="contenu_interne">
        <h2>Ajouter un nouveau frais avec forfait :</h2>
        <form method="POST" action="index.php?uc=gererCatFraisForfait&action=validerCreationCatFraisForfait">
            <label for="idCatFraisForfait">ID* :
                <input type="text" name="idCatFraisForfait" id="idCatFraisForfait">
            </label>
            <label for="libelleCatFraisForfait">Libellé* :
                <input type="text" name="libelleCatFraisForfait" id="libelleCatFraisForfait">
            </label>
            <label for="montantCatFraisForfait">Montant* :
                <input type="text" name="montantCatFraisForfait" id="montantCatFraisForfait">
            </label>
            <button type="submit" value="Valider" name="btn_valider">Valider</button>
            <a href="index.php?uc=gererCatFraisForfait&action=voirCatFraisForfait">
                <button type="button" value="Annuler" name="btn_annuler">Annuler</button>
            </a>
        </form>
    </div>
</div>