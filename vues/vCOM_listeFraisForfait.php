<div class="contenu">
    <div class="contenu_interne">
        <?php
        if (isset($messageValidationMajFrais)){
        ?>
        <div class="reussite">
            <ul>
                <li><?php echo $messageValidationMajFrais; ?></li>
            </ul>
        </div>
        <?php
        }
        if (isset($messageReportFHF)) {
        ?>
        <div class="reussite">
            <ul>
                <li><?php echo $messageReportFHF; ?></li>
            </ul>
        </div>
        <?php
        }
        ?>
        
        <h2>Fiche de frais du visiteur <?php echo $nomDuVisiteur . " " . $prenomDuVisiteur; ?> du mois <?php echo $leMoisAffichage; ?></h2>
        <p>
            Etat : <?php echo $etatFicheFrais; ?> depuis le <?php echo $dateModifFicheFrais; ?>
        </p>
        <h3>Eléments forfaitisés</h3>
        <form method="POST" action="index.php?uc=controlerFrais&action=validerMajFraisForfait">
            <input type="hidden" value="<?php echo $leVisiteur; ?>" name="idVisiteur" id="idVisiteur" readonly>
            <input type="hidden" value="<?php echo $leMois; ?>" name="mois" id="mois" readonly>
            <fieldset>
                <legend>Modifier les frais forfait</legend>
                <div class="corpsForm">
                    <table class="listeLegere">
                        <tr>
                            <th class="libelle">Libellé</th>
                            <th class="qte">Quantité</th>
                            <th class="montant">Montant unitaire</th>
                            <th class="total">Total</th>
                        </tr>
                        <?php
                        foreach ($lesFraisForfait as $unFrais){
                            $idFrais = $unFrais['idFrais'];
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
                            <td colspan="3" class="calculIntermedaireTotalFHF">Total montant frais forfait déclaré :</td>
                            <td class="calculIntermedaireTotalFHF"><?php echo $lesTotauxFicheFrais['totalGlobalFF']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="piedForm">
                    <button id="ok" type="submit" value="modifier" size="20" onclick="return confirm('Confirmez-vous la modification des frais forfait ?');" />Modifier</button>
                    <button id="annuler" type="reset" value="Effacer" size="20" />Annuler modif. </button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
