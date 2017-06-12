<div class="contenu">
    <div class="contenu_interne">
        <h3>Eléments hors forfait</h3>
        <?php
        $nbFHF = count($lesFraisHorsForfait);
        if ($nbFHF != 0){
        ?>
            <table class="listeLegere">
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>
                    <th class="montant">Montant</th>
                    <th class="action">Justificatifs téléchargés</th>
                    <th class="action">Télécharger un justificatif (*)</th>
                    <th class="action">Supprimer le frais</th>
                </tr>
                <?php
                $nbJustificatifParFrais = NULL;
                $nbTotalJustificatif = NULL;
                foreach($lesFraisHorsForfait as $unFraisHorsForfait){
                    $id = $unFraisHorsForfait['idLigneFHF'];
                    $libelle = $unFraisHorsForfait['libelleLigneFHF'];
                    $date = $unFraisHorsForfait['dateLigneFHF'];
                    $montant = $unFraisHorsForfait['montantLigneFHF'];
                    ?>
                    <tr>
                        <td><?php echo $date ?></td>
                        <td><?php echo $libelle ?></td>
                        <td><?php echo $montant ?></td>
                        <td>
                            <?php
                            $numero = NULL;
                            foreach ($lesJustificatifs as $unJustificatif){
                                $idJustificatif = $unJustificatif['idJustificatif'];
                                $cheminAcces = $unJustificatif['lienJustificatif'];
                                $dateTelechargement = $unJustificatif['dateUpload'];
                                $idFHFConcerne = $unJustificatif['idLigneFHF'];
                                if ($idFHFConcerne == $id){
                                    $numero++;
                                    ?>
                                    <a href="<?php echo $cheminAcces; ?>" target="_blank"><img class="icon" src="images/icon_file.png">Justificatif n°<?php echo $numero; ?></a> &nbsp&nbsp
                                    <form method="POST" action="index.php?uc=gererFrais&action=supprimerJustificatif">
                                        <input type="hidden" name="idJustificatif" value="<?php echo $idJustificatif; ?>" readonly>
                                        <button type="submit" value="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce justificatif?');">Supprimer</button>
                                    </form>
                                <?php
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($numero < 3){
                                ?>
                                <form method="POST" action="index.php?uc=gererFrais&action=telechargerJustificatifFrais" enctype="multipart/form-data">
                                    <input type="file" id="justificatif" name="justificatif" />
                                    <input type="hidden" name="MAX_FILE_SIZE" value="2097152" readonly> <!--Taille max fichier = 2Mo-->
                                    <input type="hidden" name="idFraisHorsForfait" value="<?php echo $id; ?>" readonly>
                                    <button type="submit" value="Télécharger">Télécharger</button>
                                </form>
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <form method="POST" action="index.php?uc=gererFrais&action=supprimerFrais">
                                <input type="hidden" name="idFrais" value="<?php echo $id; ?>" readonly>
                                <button type="submit" value="X" onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">X</button>
                            </form>
                        </td>
                    </tr>
                <?php
                }
                $nbTotalJustificatif += $nbJustificatifParFrais;
                ?>
                <tr>
                    <td colspan="5">Nombre total de justificatif téléchargé :</td>
                    <td><?php echo $nbJustificatifs; ?></td>
                </tr>
                <tr>
                    <td colspan="5" class="calculIntermediaireTotalFHF">Total montant frais hors forfait déclaré :</td>
                    <td class="calculIntermediaireTotalFHF"><?php echo $lesTotauxFicheFrais['totalGlobalFHF']; ?></td>
                </tr>
            </table>
            <div class="message_info">
                (*) Téléchargement limité à 3fichiers au maximum pour un frais
            </div>
        <?php
        }
        ?>
        <form method="POST" action="index.php?uc=gererFrais&action=validerCreationFrais" enctype="multipart/form-data">
            <fieldset>
                <legend>Ajouter un nouveau frais hors forfait</legend>
                <div class="corpsForm">
                        <p>
                            <label for="txtDateHF">Date (jj/mm/aaaa)* : </label>
                            <input type="text" id="txtDateHF" name="dateFrais" size="10" maxlength="10" value=""  />
                        </p>
                        <p>
                            <label for="txtLibelleHF">Libellé* : </label>
                            <input type="text" id="txtLibelleHF" name="libelle" size="70" maxlength="256" value="" />
                        </p>
                        <p>
                            <label for="txtMontantHF">Montant* : </label>
                            <input type="text" id="txtMontantHF" name="montant" size="10" maxlength="10" value="" />
                        </p>
                        <p>
                            <label for="justificatif">Télécharger un justificatif : </label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" readonly> <!--Taille max fichier = 2Mo-->
                            <input type="file" id="justificatif" name="justificatif" />
                        </p>
                </div>
                <div class="piedForm">
                    <input id="ajouter" type="submit" value="Ajouter" size="20" />
                    <input id="effacer" type="reset" value="Effacer" size="20" />
                </div>
            </fieldset>
        </form>
    </div>
</div>