<div class="contenu">
    <div class="contenu_interne">
        <h3>Eléments hors forfait</h3>
        <?php
        if (!empty($lesFraisHorsForfait)){ ?>
        <table class="listeLegere">
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class="montant">Montant</th>
                <th class="action">Justificatif(s)</th>
                <th class="action">Reporter</th>
                <th class="action">Refuser</th>
            </tr>
            
            <?php
            foreach($lesFraisHorsForfait as $unFraisHorsForfait){
                $idFHF = $unFraisHorsForfait['idLigneFHF'];
                $libelle = $unFraisHorsForfait['libelleLigneFHF'];
                $date = $unFraisHorsForfait['dateLigneFHF'];
                $montant = $unFraisHorsForfait['montantLigneFHF'];
                $statutLigneFHF = $unFraisHorsForfait['statutLigneFHF_refuse'];
                ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <td>
                    <?php
                    $numerotationJustificatif = 0;
                    foreach ($lesJustificatifs as $unJustificatif) {
                        $idJustificatif = $unJustificatif['idJustificatif'];
                        $lienTelechargement = $unJustificatif['lienJustificatif'];
                        if ($idFHF == $unJustificatif['idLigneFHF']){
                            $numerotationJustificatif++;
                        ?>
                             <div>
                                <a href="<?php echo $lienTelechargement ?>" target="_blank"><img class="icon" src="images/icon_file.png"><?php echo "Justificatif" . $numerotationJustificatif?></a>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </td>
                <td>
                    <form method="POST" action="index.php?uc=controlerFrais&action=reporterfrais">
                        <input type="hidden" value="<?php echo $leVisiteur; ?>" name="idVisiteur" id="idVisiteur" readonly>
                        <input type="hidden" value="<?php echo $leMois; ?>" name="mois" id="mois" readonly>
                        <input type="hidden" value="<?php echo $idFHF; ?>" name="idFrais" id="idFrais" readonly>
                        <?php
                        if ($statutLigneFHF !=1) {
                            ?>
                        <button id="reporter" type="submit" value="Reporter" size="20" onclick="return confirm('Confirmez-vous le report de ce frais ?');" />Reporter</button>
                        <?php
                        }
                        ?>
                    </form>
                </td>
                <td>
                    <form method="POST" action="index.php?uc=controlerFrais&action=supprimerfrais">
                        <input type="hidden" value="<?php echo $leVisiteur; ?>" name="idVisiteur" id="idVisiteur" readonly>
                        <input type="hidden" value="<?php echo $leMois; ?>" name="mois" id="mois" readonly>
                        <input type="hidden" value="<?php echo $idFHF; ?>" name="idFrais" id="idFrais" readonly>
                        <?php
                        if ($statutLigneFHF !=1) {
                            ?>
                        <button id="refuser" type="submit" value="Refuser" size="20" onclick="return confirm('Confirmez-vous le refus de ce frais ?');" />Refuser</button>
                    <?php
                    }
                    ?>
                    </form>  
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="5">Nombre total de justificatif téléchargé :</td>
                <td><?php echo $nbJustificatifs; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="calculIntermedaireTotalFHF">Total montant frais hors forfait déclaré :</td>
                <td class="calculIntermediaireTotalFHF"><?php echo $lesTotauxFicheFrais['totalGlobalFHF']; ?></td>
            </tr>
        </table>
        <?php
        }
        else{
            echo "Pas de frais hors forfait déclaré pour ce mois.";
        }
        ?>
        <p id="totalFiche">
            Montant total des frais déclarés = <?php echo $lesTotauxFicheFrais['totalGlobalFiche'];?>€
        </p>
        <p>
        <form method="POST" action="index.php?uc=controlerFrais&action=validerFiche" id="form_actionFinale">
            <input type="hidden" value="<?php echo $leVisiteur; ?>" name="idVisiteur" id="idVisiteur" readonly>
            <input type="hidden" value="<?php echo $leMois; ?>" name="mois" id="mois" readonly>
            <button id="valider" type="submit" value="Valider fiche de frais" size="20" onclick="return confirm('Confirmez-vous la validation de la fiche de frais du mois <?php echo $leMoisAffichage; ?> pour le visiteur <?php echo $nomDuVisiteur . " " . $prenomDuVisiteur; ?> pour un montant total de <?php echo $lesTotauxFicheFrais['totalGlobalFiche'];?>€ ?');" />Valider fiche de frais</button>
        </form>
        </p>
    </div>
</div>

