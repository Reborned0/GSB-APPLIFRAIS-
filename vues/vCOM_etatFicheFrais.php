<div class="contenu">
    <div class="contenu_interne">
        <h2>Fiche de frais du visiteur <?php echo $nomDuVisiteur . " " . $prenomDuVisiteur; ?> du mois <?php echo $leMoisAffichage; ?></h2>
        <div class="encadre">
            <p>Etat : <?php echo $libEtat?> depuis le <?php echo $dateModif?> <br> Montant validé : <?php echo $montantValide?>€ par le comptable <?php echo $nomDuComptable . " " . $prenomDuComptable; ?></p>
            <h3>Eléments forfaitisés</h3>
            <table class="listeLegere">
                <tr>
                    <th class="libelle">Libellé</th>
                    <th class="qte">Quantité</th>
                </tr>
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idFrais'];
                    $libelle = $unFrais['libelleFrais'];
                    $quantite = $unFrais['quantite'];
                    ?>
                <tr>
                    <td><?php echo $libelle ?></td>
                    <td><?php echo $quantite ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
            <h3>Eléments hors forfaits</h3>
            <table class="listeLegere">
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>
                    <th class="montant">Montant</th>
                    <th class="montant">Justificatif(s) transmis</th>
                </tr>
                <?php
                foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                    $idFHF = $unFraisHorsForfait['idLigneFHF'];
                    $date = $unFraisHorsForfait['dateLigneFHF'];
                    $libelle = $unFraisHorsForfait['libelleLigneFHF'];
                    $montant = $unFraisHorsForfait['montantLigneFHF'];
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
                          if ($idFHF == $unJustificatif['idLigneFHF']) {
                              $numerotationJustificatif++;
                              ?>
                                <div>
                                    <a href="<?php echo $lienTelechargement ?>" target="_blank"><img class="icon" src="images/icon_file.png"><?php echo "Justificatif " . $numerotationJustificatif?></a>
                                 </div>
                          <?php
                          }
                      }
                      ?>
                    </td>
                </tr>
                <?php
            }
            ?>
                <tr>
                    <td colspan="3">Nombre total de justificatif transmis :</td>
                    <td><?php echo $nbJustificatifs; ?></td>
                </tr>
            </table>
        </div>
        <form method="POST" action="index.php?uc=suivreFrais&action=mettreEnPaiementFicheFrais" id="form_actionFinale">
            <input type="hidden" value="<?php echo $leVisiteur; ?>" name="idVisiteur" id="idVisiteur" readonly>
            <input type="hidden" value="<?php echo $leMois; ?>" name="mois" id="mois" readonly>
            <?php
            if ($idEtatFicheFrais == "VA") {
                ?>
            <p>
                <button id="payer" type="submit" value="Mettre en paiement" size="20" onclick="return confirm('Confirmez-vous la mise en paiement pour remboursement de la fiche de frais du mois <?php echo $leMoisAffichage; ?> pour le visiteur <?php echo $nomDuVisiteur . " " . $prenomDuVisiteur; ?> pour un montant total de <?php echo $lesTotauxFicheFrais['totalGlobalFiche'];?>€ ?')" />Mettre en paiement</button>
            </p>
           <?php
            }
            ?>
        </form>
    </div>
</div>

