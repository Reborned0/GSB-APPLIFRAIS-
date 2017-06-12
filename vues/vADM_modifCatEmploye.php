<div class="contenu">
    <div class="contenu_interne">
        <h2>Modifier les catégories "Type employé" :</h2>
        <form method="POST" action="index.php?uc=gererCatEmploye&action=validerMajCatEmploye">
            <table class="listeLegere listeLegereNonCentree">
                <tr>
                    <th>ID</th>
                    <th>Libellé</th>
                </tr>
                <?php
                foreach ($lesCatEmploye as $catEmploye) {
                    $id = $catEmploye['idTypeEmploye'];
                    $libelle = $catEmploye['libelleTypeEmploye'];
                ?>
                <tr>
                    <td><?php echo $id; ?><input type="hidden" name="idCatEmploye" value="<?php echo $id; ?>" readonly></td>
                    <td><input type="text" name="libelleCatEmploye" value="<?php echo $libelle; ?>"></td>
                </tr>
                <?php
                }
                ?>
            </table>
            <button type="submit" value="Valider" name="btn_valider">Valider</button>
            <a href="index.php?uc=gererCatEmployeaction=voirCatEmploye">
            <button type="button" value="Annuler" name="btn_annuler">Annuler</button>
            </a>
        </form>
    </div>
</div>
