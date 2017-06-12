    <!-- Division pour le sommaire -->
    <div id="menuGauche">
        <div id="infosUtil">
            <h1>ESPACE COMPTABLE</h1>
    
        </div>  
        <ul id="menuList">
            <li class="nomEmploye">
                Comptable :
		<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']; ?>
            </li>
            <li class="smenu">
              <a href="index.php?uc=controlerFrais&action=selectionnerVisiteur" title="Valider fiche de frais ">Valider fiche de frais</a>
            </li>
            <li class="smenu">
              <a href="index.php?uc=suivreFrais&action=selectionnerVisiteur" title="Suivre paiement fiches de frais">Suivre paiement fiches de frais</a>
            </li>
 	    <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
            </li>
        </ul>
        
    </div>
    