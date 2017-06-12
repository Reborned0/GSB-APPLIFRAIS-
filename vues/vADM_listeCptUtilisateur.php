<div class="contenu">
    <div class="contenu_interne">
        <h2>Comptes utilisateur GSB :</h2>
        <a href="index.php?uc=gererCptUtilisateur&action=ajouterCptUtilisateur"><button type="button" value="Nouveau" name="btn_ajouter">Nouveau</button></a>
        <table border width="100%" class="listeLegere">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Login</th>
                <th>Adresse</th>
                <th>date d'embauche</th>
                <th>Type de véhicule</th>
                <th>Type employé</th>
                <th>Etat compte</th>
                <th>Date dernière MAJ fiche</th>
                <th><img src="images/icon_edit.png"</th>
                <th><img src="images/icon_delete.png"</th>
            </tr>
            <?php
            foreach ($lesCptUtilisateur as $cptUtilisateur) {
                $id = $cptUtilisateur['idEmploye'];
                $nom = $cptUtilisateur['nom'];
                $prenom = $cptUtilisateur['prenom'];
                $login = $cptUtilisateur['login'];
                $adresse = $cptUtilisateur['adresse'];
                $cp = $cptUtilisateur['codePostal'];
                $ville = $cptUtilisateur['libelleVille'];
                $dateEmbauche = $cptUtilisateur['dateEmbauche'];
                $libelleTypeVehicule = $cptUtilisateur['libelleTypeVehicule'];
                $typeEmployeId = $cptUtilisateur['idTypeEmploye'];
                $typeEmployeLibelle = $cptUtilisateur['libelleTypeEmploye'];
                $dateDerniereModif = $cptUtilisateur['dateModifFicheE'];
                ($cptUtilisateur['cptActif'] == 1) ? $etatCpt = "Actif" : $etatCpt = "Inactif";
            ?>
            <tr>
                <td align="center"><?php echo $id; ?></td>
                <td align="center"><?php echo $nom; ?></td>
                <td align="center"><?php echo $prenom; ?></td>
                <td align="center"><?php echo $login; ?></td>
                <td align="center"><?php echo $adresse . " " . $cp . " " . $ville; ?></td>
                <td align="center"><?php echo $dateEmbauche; ?></td>
                <td align="center"><?php echo $libelleTypeVehicule; ?></td>
                <td align="center"><?php echo $typeEmployeId . " - " . $typeEmployeLibelle; ?></td>
                <td align="center"><?php echo $etatCpt; ?></td>
                <td align="center"><?php echo $dateDerniereModif; ?></td>
                <td align="center">
                    <form method="POST" action="index.php?uc=gererCptUtilisateur&action=modifierCptUtilisateur">
                        <input type="hidden" value="<?php echo $id; ?>" name="idUser" id="idUser" readonly>
                        <button type="submit" value="Modifier" name="btn_modifier">Modifier</button>
                    </form>
                </td>
                <td align="center">
                    <form method="POST" action="index.php?uc=gererCptUtilisateur&action=validerSuppressionCptUtilisateur">
                        <input type="hidden" value="<?php echo $id; ?>" name="idUser" id="idUser" readonly>
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
