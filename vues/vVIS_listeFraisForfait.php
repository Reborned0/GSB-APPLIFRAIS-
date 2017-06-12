<div class="contenu contenu_premier">
    <h2>Renseigner ma fiche de frais du mois <?php echo $moisActuel."-".$anneeActuel ?></h2>
    <div class="contenu_interne">
        <p>
            Etat : <?php echo $etatFicheFrais; ?> depuis le <?php echo $dateModifFicheFrais; ?>
        </p>
        <h3>Eléments forfaitisés</h3>
        <form method="POST" action="index.php?uc=gererFrais&action=validerMajFraisForfait">
            <fieldset>
                <legend>Déclarer les frais forfaits</legend>
                <div class="corpsForm">
                    <table class="listeLegere">
                        <tr>
                            <th class="libelle">Libellé</th>
                            <th class="qte">Quantité</th>
                            <th class="montant">Montant unitaire</th>
                            <th class="total">Total</th>         
                        </tr>
                        <?php
                        foreach ($lesFraisForfait as $unFrais) {
                            $idFrais = $unFrais['idfrais'];
                            $libelle = $unFrais['libelleFrais'];
                            $quantite = $unFrais['quantite'];
                            $montant = $unFrais['montantFF'];
                            ?>
                        <tr>
                            <td><?php echo $libelle ?></td>
                            <td><input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais?>]" value="<?php echo $quantite?>" ></td>
                            <td><?php echo $montant ?></td>
                            <td><?php echo $lesTotauxFicheFrais["$idFrais"] ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="calculIntermediaireTotalFHF">Total montant frais forfait déclaré :</td>
                            <td class="calculIntermediaireTotalFHF"><?php echo $lesTotauxFicheFrais['totalGlobalFF']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="piedForm">
                    <input id="ok" type="submit" value="Valider" size="20" />
                    <input id="annuler" type="reset" value="Effacer" size="20" />
                </div>
            </fieldset>
        </form>
    </div>
</div>

