<div class="contenu">
    <div class="contenu_interne">
        <h2>Ajouter un nouvel utilisateur :</h2>
        <form method="POST" action="index.php?uc=gererCptUtilisateur&action=validerCreationCptUtilisateur">
            <label for="nomEmploye">Nom* :
               <input type="text" name="nomEmploye" id="nomEmploye">
            </label>
            <label for="prenomEmploye">Prénom* :
                <input type="text" name="prenomEmploye" id="prenomEmploye">
            </label>
            <label for="adresseEmploye">Adresse* :
                <input type="text" name="adresseEmploye" id="adresseEmploye">
            </label>
            <label for="localisation">CP - Ville* :
                <select name="localisation" id="localisation">
                    <option value="0"></option>
                    <?php
                    for ($i=0;$i<count($localisation);$i++){
                        echo '<option value="' . $localisation[$i]['idLocalisation'] . '">' . $localisation[$i]['libelleVille'] . ' [' . $localisation[$i]['codePostal'] . ']</option>';
                    }
                    ?>
                </select>
            </label>
            <label for="loginEmploye">Login* :
                <input type="text" name="loginEmploye" id="loginEmploye">
            </label>
            <label for="mdpEmploye">Mot de passe* :
                <input type="text" name="mdpEmploye" id="mdpEmploye">
            </label>
            <label for="dateEmbaucheEmploye">Date d'embauche (jj/mm/aaaa)* :
                <input type="date" name="dateEmbaucheEmploye" id="dateEmbaucheEmploye">
            </label>
            <label for="typeEmploye">Type employé* :
                <select name="typeEmploye" id="typeEmploye">
                    <option value="0"></option>
                    <?php
                    for ($i=0;$i<count($lesCatEmploye);$i++){
                        echo '<option value="' . $lesCatEmploye[$i]['idTypeEmploye'] . '">' . $lesCatEmploye[$i]['libelleTypeEmploye'] . '</option>';
                    }
                    ?>
                </select>
            </label>
            <label for="fraisKm">Type du véhicule (si véhicule)* :
                <select name="fraisKm" id="fraisKm">
                    <option value="0"></option>
                    <?php
                    for ($i=0;$i<count($lesCatFraisKm);$i++){
                        echo '<option value="' . $lesCatFraisKm[$i]['idTypeVehicule'] . '">' . $lesCatFraisKm[$i]['libelleTypeVehicule'] . '</option>';
                    }
                    ?>
                </select>
            </label>
            <label for="cptActif">
                <input type="radio" name="activationCptEmploye" id="cptActif" value="1" checked>Compte actif
            </label>
            <label for="cptInactif">
                <input type="radio" name="activationCptEmploye" id="cptInactif" value="0">Compte inactif
            </label>
            <button type="submit" value="Valider" name="btn_valider">Valider</button>
            <a href="index.php?uc=gererCptUtilisateur&action=voirCptUtilisateur">
                <button type="button" value="Annuler" name="btn_annuler">Annuler</button>
            </a>
        </form>
    </div>
</div>

