<div class="contenu">
    <div class="contenu_interne">
        <h2>Catégorie "Type véhicule" :</h2>
        <a href="index.php?uc=gererCatFraisKm&action=ajouterCatFraisKm"><button type="button" value="Nouveau" name="btn_ajouter">Nouveau</button></a>
        <table border width="100%" class="listeLegere">
            <tr>
                <th>Libellé</th>
                <th><img src="images/icon_edit.png"</th>
                <th><img src="images/icon_delete.png"</th>
            </tr>
            <?php
            foreach ($lesCatFraisKm as $catFraisKm) {
                $id = $catFraisKm['idTypeVehicule'];
                $libelle = $catFraisKm['libelleTypeVehicule'];
            ?>
                    <tr>
                        <td align="center"><?php echo $libelle; ?></td>
                        <td align="center">
                            <form method="POST" action="index.php?uc=gererCatFraisKm&action=modifierCatFraisKm">
                                <input type="hidden" value="<php echo $id; ?>" name="idCatFraisKm" id="idCatFraisKm" readonly>
                                <button type="submit" value="Modifier" name="btn_modifier">Modifier</button>
                            </form>
                        </td>
                        <td align="center">
                            <form method="POST" action="index.php?uc=gererCatFraisKm&action=validerSuppressionCatFraisKm">
                                <input type="hidden" value="<php echo $id; ?>" name="idCatFraisKm" id="idCatFraisKm" readonly>
                                <button type="submit" value="Supprimer" name="btn_supprimer" onclick="return(confirm('Confirmer la suppression'));">Supprimer</button>
                            </form>
                        </td>
                    </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>

