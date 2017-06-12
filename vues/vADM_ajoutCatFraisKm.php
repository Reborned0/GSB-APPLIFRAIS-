<div class="contenu">
    <div class="contenu_interne">
        <h2>Ajouter un nouveau type de véhicule :</h2>
        <form method="POST" action="index.php?uc=gererCatFraisKm&action=validerCreationCatFraisKm">
            <label for="libelleCatfraisKm">Libellé* :
                <input type="text" name="libelleCatFraisKm" id="libelleCatFraisKm">
            </label>
            <button type="submit" value="Valider" name="btn_valider">Valider</button>
            <a href="index.php?uc=gererCatFraisKm&action=voirCatFraisKm">
                <button type="button" value="Annuler" name="btn_annuler">Annuler</button>
            </a>
        </form>
    </div>
</div>
