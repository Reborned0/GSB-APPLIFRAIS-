<!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
        <h1>ESPACE ADMINISTRATEUR</h1>
    </div>
        
        <ul id="menuList">
            <li class="nomEmploye">
                Administrateur : <?php echo $_SESSION['prenom']."  ".$_SESSION['nom'];?>
            </li>
           <li class="smenu">
              <a href="index.php?uc=gererCatEmploye&action=voirCatEmploye" title="Gérer catégorie type employé (ajout, modif, suppression) ">Gérer catégorie "Type employé"</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=gererCatFraisKm&action=voirCatFraisKm" title="Gérer catégorie type véhicule (ajout, modif, suppression) ">Gérer catégorie "Type véhicule"</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=gererCatFraisForfait&action=voirCatFraisForfait" title="Gérer catégorie frais forfaitisé (ajout, modif, suppression) ">Gérer catégorie "Frais forfaitisé"</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=gererCptUtilisateur&action=voirCptUtilisateur" title="Gérer les comptes utilisateur GSB (ajout, modif, suppression)">Gérer les comptes utilisateur"</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
        
    </div>

