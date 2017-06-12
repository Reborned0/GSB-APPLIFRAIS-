<?php
include("vues/v2_sommaireCOM.php");
$idEmploye = $_SESSION['idVisiteur'];
$typeEmploye = $_SESSION['type'];
$mois = getMois(date("d/m/Y"));
$dateActuelle = date('Ymd');
$jourActuel = substr($dateActuelle,6,2);
$moisActuel = substr($dateActuelle,4,2);
$anneeActuel = substr($dateActuelle,0,4);
$periodeActuelle = $anneeActuel . $moisActuel;
if(!isset($_GET['action'])){
    $_GET['action'] = 'selectionnerVisiteur';
}
$action = $_GET['action'];
switch($action){
    case 'selectionnerVisiteur':{
        $lstVisiteur = $pdo->getLesCptUtilisateur(NULL,"VIS");
        include("vues/v_listeVisiteur.php");
        break;
    }
    case 'selectionnerMois':{
        $lstVisiteur = $pdo->getLesCptUtilisateur(NULL,"VIS");
        include("vues/v_listeVisiteur.php");
        $leVisiteur = @$_POST['lstVisiteur'];
        $lesMois = $pdo->getLesMoisFicheFraisValidee($leVisiteur,$periodeActuelle);
        include("vues/v_listeMois.php");
        break;
    }
    case 'voirFicheFrais':{
        $lstVisiteur = $pdo->getLesCptUtilisateur(NULL,"VIS");
        isset($_POST['idVisiteur']) ? $leVisiteur = $_POST['idVisiteur'] : $leVisiteur = NULL ;
        $infoVisiteur = $pdo->getLesCptUtilisateur($leVisiteur);
        foreach ($infoVisiteur as $info){
            $nomDuVisiteur = $info['nom'];
            $prenomDuVisiteur = $info['prenom'];
        }
        $lesMois = $pdo->getLesMoisDisponibles($leVisiteur,$periodeActuelle);
        isset($_POST['lstMois']) ? $leMois = $_POST['lstMois'] : $leMois = NULL ;
        $leMoisAffichage = formatageIdDate($leMois);
        break;
    }
    case 'mettreEnPaiementFicheFrais':{
        $lstVisiteur = $pdo->getLesCptUtilisateur(NULL,"VIS");
        $leVisiteur = $_POST['idVisiteur'];
        $leMois = $_POST['mois'];
        $infoVisiteur = $pdo->getLesCptUtilisateur($leVisiteur);
        foreach ($infoVisiteur as $info){
            $nomDuVisiteur = $info['nom'];
            $prenomDuVisiteur = $info['prenom'];
        }
        $lesMois = $pdo->getLesMoisDisponibles($leVisiteur,$periodeActuelle);
        $result = $pdo->majEtatFicheFrais($leVisiteur,$leMois,'RB');
        $leMoisAffichage = formatageIdDate($leMois);
        break;
    }
}
if ($action == 'voirFicheFrais' || $action == 'mettreEnPaiementFicheFrais'){
    $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur,$leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur,$leMois);
    $lesFraisForfaitKm = $pdo->getLesCptUtilisateur($leVisiteur); /*Permet de récupérer le frais KM forfait propre à chaque visiteur en fonction de son type de véhicule (diesel ou essence / 4CV, 5CV, 6CV, ...)*/
    $lesJustificatifs = $pdo->getLesJustificatifs($leVisiteur);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois);
    $dateModifFicheFrais = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    $idEtatFicheFrais = $lesInfosFicheFrais['idEtat'];
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $dateModif = $lesInfosFicheFrais['dateModif'];
    $dateModif = dateAnglaisVersFrancais($dateModif);
    $numAnnee = substr($leMois,0,4);
    $numMois = substr($leMois,4,2);
    $lesTotauxFicheFrais = $pdo->calculElementIntermediaire($leVisiteur,$leMois);
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $nomDuComptable = $lesInfosFicheFrais['nomComptable'];
    $prenomDuComptable = $lesInfosFicheFrais['prenomComptable'];
    include("vues/v_listeVisiteur.php");
    include("vues/v_listeMois.php");
    include("vues/vCOM_etatFicheFrais.php");
}
?>
