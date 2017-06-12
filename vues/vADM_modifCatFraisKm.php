<div class="contenu">
    <div class="contenu_interne">
        <h2>Modifier les catégories "Type véhicule" :</h2>
        <form method="POST" action="index.php?uc=gererCatFraisKm&action=validerMajCatFraisKm">
            <?php
            foreach ($lesCatFraisKm as $catFraisKm) {
                $id = $catFraisKm['idTypeVehicule'];
                $libelle = $catFraisKm['libelleTypeVehicule'];
            ?>
            <table class="listeLegere listeLegereNonCentree">
                <tr>
                    <th>ID</th>
                    <th>Libellé</th>
                </tr>
                <tr>
                    <td><?php echo $id; ?><input type="hidden" name="idCatFraisKm" value="<?php echo $id; ?>" readonly></td>
                    <td><input type="text" name="libelleCatFraisKm" value="<?php echo $libelle; ?>"></td>
                </tr>
            </table>
            <?php
            }
            ?>
            <button type="submit" value="Valider" name="btn_valider">Valider</button>
            <a href="index.php?uc=gererCatFraisKm&action=voirCatFraisKm"><button type="button" value="Annuler" name="btn_annuler">Annuler</button></a>
        </form>
    </div>
</div>
    
    

