<div class="contenu">
    <div class="contenu_interne">
        <h2>Modifier les catégories "Frais forfaitisés" :</h2>
        <form method="POST" action="index.php?uc=gererCatFraisForfait&action=validerMajCatFraisForfait">
            <table class="listeLegere listeLegereNonCentree">
                <tr>
                    <th>ID</th>
                    <th>Libellé*</th>
                    <th>Montant*</th>
                </tr>
                <?php
                foreach ($lesCatFraisForfait as $catFraisForfait) {
                    $id = $catFraisForfait['idFraisForfaitise'];
                    $libelle = $catFraisForfait['libelleFF'];
                    $montant = $catFraisForfait['montantFF'];
                ?>
                <tr>
                    <td><?php echo $id; ?><input type="hidden" name="idCatFraisForfait" value="<?php echo $id; ?>" readonly></td>
                    <td><input type="text" name="libelleCatFraisForfait" value="<?php echo $libelle; ?>"></td>
                    <td><input type="text" name="montantCatFraisForfait" value="<?php echo $montant; ?>"></td>
                </tr>
                <?php
                }
                ?>
            </table>
            <button type="submit" value="Valider" name="btn_valider">Valider</button>
            <a href="index.php?uc=gererCatFraisForfait&action=voirCatFraisForfait"><button type="button" value="Annuler" name="btn_annuler">Annuler</button></a>
        </form>
    </div>
</div>

