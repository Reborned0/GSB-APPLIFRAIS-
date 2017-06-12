<div class="contenu">
    <div class="contenu_interne">
        <h2>Modification compte utilisateur :</h2>
        <form method="POST" action="index.php?uc=gererCptUtilisateur&action=validerMajCptUtilisateur">
            <input type="hidden" name="idEmploye" id="idEmploye" value="<?php echo $lesCptUtilisateur[0]['idEmploye']; ?>" readonly>
            <label for="nomEmploye">Nom* :
                <input type="text" name="nomEmploye" id="nomEmploye" value="<?php echo $lesCptUtilisateur[0]['nom']; ?>">
            </label>
            <label for="prenomEmploye">Prénom* :
                <input type="text" name="prenomEmploye" id="prenomEmploye" value="<?php echo $lesCptUtilisateur[0]['prenom']; ?>">
            </label>
            <label for="adresseEmploye">Adresse* :
                <input type="text" name="adresseEmploye" id="adresseEmploye" value="<?php echo $lesCptUtilisateur[0]['adresse']; ?>">
            </label>
            <label for="localisation">CP - Ville* :
                <select name="localisation" id="localisation">
                    <?php
                    for ($i=0;$i<count($localisation);$i++) {
                        if ($lesCptUtilisateur[0]['idLocalisation'] == $localisation[$i]['idLocalisation']){
                             $select = "selected";
                        }
                         else{
                             $select = "";
                         }
                        echo '<option value="' . $localisation[$i]['idLocalisation'] . '"' . $select . '>' . $localisation[$i]['libelleVille'] . '[' . $localisation[$i]['codePostal'] . ']</option>';
                     }
                     ?>
                </select>
            </label>
            <label for="dateEmbaucheEmploye">Date d'embauche (jj/mm/aaaa)* :
                <input type="date" name="dateEmbaucheEmploye" id="dateEmbaucheEmploye" value="<?php echo dateAnglaisVersFrancais($lesCptUtilisateur[0]['dateEmbauche']); ?>">
            </label>
            <label for="typeemploye">Type employé* :
                <select name="typeEmploye" id="typeEmploye">
                 <?php
                 for ($i=0;$i<count($lesCatEmploye);$i++){
                     if ($lesCptUtilisateur[0]['idEmploye'] == $lesCatEmploye[$i]['idEmploye']){
                         $select = "selected";
                     }
                     else{
                         $select = "";
                     }
                     echo '<option value="' . $lesCatEmploye[$i]['idEmploye'] . '"' . $select . '>' . $lesCatEmploye[$i]['libelleTypeEmploye'] . '</option>';
                 }
                 ?>
                </select>
            </label>
            <label for="fraisKm">Type du véhicule (si visiteur) :
                <select name="fraisKm" id="fraisKm">
                    <option value="0"></option>
                <?php
                for ($i=0;$i<count($lesCatFraisKm);$i++){
                    if ($lesCptUtilisateur[0]['idTypeVehicule'] == $lesCatFraisKm[$i]['idtypeVehicule']){
                        $select = "selected";
                    }
                    else{
                        $select = "";
                    }
                    echo '<option value="' . $lesCatFraisKm[$i]['idTypeVehicule'] . '"' . $select . '>' . $lesCatFraisKm[$i]['libelleTypeVehicule'] . '</option>';
                }
                ?>
                </select>
            </label>
            <?php
            if ($lesCptUtilisateur[0]['cptActif'] == 1){
                echo '  <label for="cptActif"><input type="radio" name="activationCptEmploye" id="cptActif" value="1" checked>Compte actif</label>
                        <label for="cptInactif"><input type="radio" name="activationCptEmploye" id="cptInactif" value="0" checked>Compte inactif</label>';
            }
            else{
                echo '  <label for="cptActif"><input type="radio" name="activationCptEmploye" id="cptActif" value="1" checked>Compte actif</label>
                        <label for="cptInactif"><input type="radio" name="activationCptEmploye" id="cptInactif" value="0" checked>Compte inactif</label>';
            }
            ?>
            <label for="loginEmploye">Login (non modifiable) :
                <input type="text" name="loginEmploye" id="loginEmploye" value="<?php echo $lesCptUtilisateur[0]['login']; ?>" readonly>
            </label>
            <label for="mdpEmploye">Réinitialisation du mot de passe du compte :
                <input type="text" name="mdpEmploye" id="mdpEmploye">
            </label>
            <button type="submit" value="Valider" name="btn_valider">Valider</button>
            <a href="index.php?uc=gererCptUtilisateur"><button type="button" value="Annuler" name="btn_annuler"></button></a>
        </form>
    </div>
</div>

