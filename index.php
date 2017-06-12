<?php
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");
include("vues/v1_entete.php") ;
session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
$leJourDuMois = intval(date('d'));
$estDebutCampagneComptableDuMois = verifEstDebutCampagneDuMois($leJourDuMois); //1er jour de la campagne comptable = 10 du mois 
if ($estDebutCampagneComptableDuMois){
    $pdo->clotureLesFichesFrais();
}
if(!isset($_GET['uc']) || !$estConnecte){
     $_GET['uc'] = 'connexion';
}	 
$uc = $_GET['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");
        break;
	}
        /*ACTIONS VISITEURS*/
	case 'gererFrais' :{
		include("controleurs/cVIS_gererFrais.php");
        break;
	}
	case 'etatFrais' :{
		include("controleurs/cVIS_etatFrais.php");
        break; 
	}
        /*FIN ACTIONS VISITEURS*/
        
        /*ACTIONS COMPTABLES*/
        case 'controlerFrais' :{
                include("controleurs/cCOM_controlerFrais.php");
                break;
        }
        case 'suivreFrais' :{
            include("controleurs/cCOM_suivreFrais.php");
            break;
        }
        /*FIN ACTIONS COMPTABLES*/
        
        /*ACTIONS ADMIN*/
        case 'gererCatEmploye' :{
            include("controleurs/cADM_gererCatEmploye.php");
            break;
        }
        case 'gererCatFraisKm' :{
            include("controleurs/cADM_gererCatTypeVehicule.php");
            break;
        }
        case 'gererCatFraisForfait' :{
            include("controleurs/cADM_gererCatFraisForfait.php");
            break;
        }
        case 'gererCptUtilisateur' :{
            include("controleurs/cADM_gererCptUtilisateur.php");
            break;
        }
        /*FIN ACTIONS ADMIN*/
}
include("vues/v5_pied.php") ;
?>

