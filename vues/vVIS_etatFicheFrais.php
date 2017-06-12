<div class="contenu">
    <div class="contenu_interne">
        <h2>Fiche de frais du mois <?php echo $numMois . "-" . $numAnnee; ?></h2>
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
                    <th>Télécharger un justificatif (*)</th>
                </tr>
                <?php
                $moisSuivant = (int) $numMois + 1;
                $moisSuivant = "0" . $moisSuivant;
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
                      $messageEnvoiJustificatif = "(*) Envoi de justificatif admis jusqu'au 10 du mois suivant la déclaration des frais. Vous pouvez encore télécharger des justificatifs pour ce mois. Téléchargement limité à 3  fichiers maximum pour un frais";
                      foreach ($lesJustificatifs as $unJustificatif) {
                          $idJustificatif = $unJustificatif['idJustificatif'];
                          $lienTelechargement = $unJustificatif['lienJustificatif'];
                          if ($idFHF == $unJustificatif['idLigneFHF']) {
                              $numerotationJustificatif++;
                              ?>
                                <div>
                                    <a href="<?php echo $lienTelechargement ?>" target="_blank"><img class="icon" src="images/icon_file.png"><?php echo "Justificatif " . $numerotationJustificatif?></a>
                                    <?php
                                    if ($moisActuel == $moisSuivant && ($jourActuel >= "01" && $jourActuel < "10")) {
                                        ?>
                                    <form method="POST" action="index.php?uc=etatFrais&action=supprimerJustificatif">
                                        <input type="hidden" name="idJustificatif" value="<?php echo $idJustificatif; ?>" readonly>
                                        <input type="hidden" name="mois" value="<?php echo $leMois; ?>" readonly>
                                        <button type="submit" value="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce justificatif? ');">Supprimer</button>
                                    </form>
                                    <?php
                                    }
                                    ?>
                                </div>
                          <?php
                          }
                      }
                      ?>
                    </td>
                    <td>
                        <?php
                        if ($numerotationJustificatif == 0 || $numerotationJustificatif < 3){
                            $id = $idFHF;
                            if ($moisActuel == $moisSuivant && ($jourActuel < "10")){
                            ?>
                        <form method="POST" action="index.php?uc=etatfrais&action=telechargerJustificatiffrais" enctype="multipart/form-data">
                            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" readonly> <!--Taille max fichier = 2Mo-->
                            <input type="file" id="justificatif" name="justificatif"/>
                            <input type="hidden" name="idFraisHorsForfait" value="<?php echo $id; ?>" readonly>
                            <input type="hidden" name="mois" value="<?php echo $leMois; ?>" readonly>
                            <button type="submit" value="Télécharger"</button>
                        </form>
                            <?php
                            }
                            else{
                                $messageEnvoiJustificatif = "(*) Envoi de justificatif admis jusqu'au 10  du mois suivant la déclaration des frais. Vous n'avez plus la possibilité de télécharger de justificatif pour ce mois. ";
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
                <tr>
                    <td colspan="4">Nombre total de justificatif téléchargé :</td>
                    <td><?php echo $nbrJustificatif; ?></td>
                </tr>
            </table>
            <?php
            if (isset($messageEnvoiJustificatif)){
            ?>
            
            <div class="message_info">
                <?php echo $messageEnvoiJustificatif; ?>
            </div>
            <?php
            }
            ?>     
        </div>
    </div>
</div>

