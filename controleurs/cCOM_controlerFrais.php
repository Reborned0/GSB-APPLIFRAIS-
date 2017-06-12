<?php
include("vues/v2_sommaireCOM.php");
$idEmploye = $_SESSION['idVisiteur'];
$typeEmploye = $_SESSION['type'];
$mois = getMois(date("d/m/Y"));
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
        $leVisiteur = $_POST['1stVisiteur'];
        $lesMois = $pdo->getLesMoisFicheFraisCloturee($leVisiteur);
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
        $lesMois = $pdo->getLesMoisFicheFraisCloturee($leVisiteur);
        isset($_POST['lstMois']) ? $leMois = $_POST['lstMois'] : $leMois = NULL ;
        $leMoisAffichage = formatageIdDate($leMois);
        break;
    }
    case 'validerMajFraisForfait':{
        $lstVisiteur = $pdo->getLesCptUtilisateur(NULL,"VIS");
        $leVisiteur = $_POST['idVisiteur'];
        $infoVisiteur = $pdo->getLesCptUtilisateur($leVisiteur);
        foreach ($infoVisiteur as $info){
            $nomDuVisiteur = $info['nom'];
            $prenomDuVisiteur = $info['prenom'];
        }
        $lesMois = $pdo->getLesMoisFicheFraisCloturee($leVisiteur);
        $leMois = $_POST['mois'];
        $leMoisAffichage = formatageIdDate($leMois);

        $lesFrais = $_POST['lesFrais'];
        if(lesQteFraisValides($lesFrais)){
            $result = $pdo->majFraisForfait($leVisiteur,$leMois,$lesFrais);
            if ($result){
                $messageValidationMajFrais = "Mise à jour des frais forfait réussie";
            }
        }
        else{
            ajouterErreur("Les valeurs des frais doivent être numériques");
            include("vues/v4_erreurs.php");
        }
        break;
    }
    case 'reporterFrais':{
        $lstVisiteur = $pdo->getLesCptUtilisateur(NULL,"VIS");
        $leVisiteur = $_POST['idVisiteur'];
        $infoVisiteur = $pdo->getLesCptUtilisateur($leVisiteur);
        foreach ($infoVisiteur as $info){
            $nomDuVisiteur = $info['nom'];
            $prenomDuVisiteur = $info['prenom'];
        }
        $lesMois = $pdo->getLesMoisFicheFraisCloturee($leVisiteur);
        $leMois = $_POST['mois'];
        $leMoisAffichage = formatageIdDate($leMois);

        $idFrais = $_POST['idFrais'];
        $result = $pdo->reporterFraisHorsForfaitSurFicheFraisSuivante($idFrais,$leVisiteur,$mois,$leMois);
        if ($result){
            $messageReportFHF = "Report du frais hors forfait réussi.";
        }
        break;
    }
    case 'supprimerFrais':{
        $lstVisiteur = $pdo->getLesCptUtilisateur(NULL,"VIS");
        $leVisiteur = $_POST['idVisiteur'];
        $infoVisiteur = $pdo->getLesCptUtilisateur($leVisiteur);
        foreach ($infoVisiteur as $info){
            $nomDuVisiteur = $info['nom'];
            $prenomDuVisiteur = $info['prenom'];
        }
        $lesMois = $pdo->getLesMoisFicheFraisCloturee($leVisiteur);
        $leMois = $_POST['mois'];
        $leMoisAffichage = formatageIdDate($leMois);

        $idFrais = $_POST['idFrais'];
        $result = $pdo->refuserFraisHorsForfait($idFrais,$leVisiteur,$mois,$leMois);
        if ($result){
            $messageReportFHF = "Refus du frais hors forfait réussi.";
        }
        break;
    }
    case 'validerFiche':{
        $leVisiteur = $_POST['idVisiteur'];
        $leMois = $_POST['mois'];
        $lesTotauxFicheFrais = $pdo->calculElementIntermediaire($leVisiteur,$leMois);
        $pdo->majEtatFicheFrais($leVisiteur,$leMois,'VA',$lesTotauxFicheFrais['totalGlobalFiche'],$idEmploye);
        header('Location: index.php?uc=controlerFrais&action=selectionnerVisiteur');
        break;
    }
}
if ($action == 'voirFicheFrais' || $action == 'validerMajFraisForfait' || $action == 'reporterFrais' || $action == 'supprimerFrais'){
    $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur,$leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur,$leMois);
    $lesFraisForfaitKm = $pdo->getLesCptUtilisateur($leVisiteur); /*Permet de récupérer le frais KM forfait propre à chaque visiteur en fonction de son type de véhicule (diesel ou essence / 4CV, 5CV, 6CV, ...)*/
    $lesJustificatifs = $pdo->getLesJustificatifs($leVisiteur);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois);
    $dateModifFicheFrais = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    $idFicheFrais = $lesInfosFicheFrais['idEtat'];
    $etatFicheFrais = $lesInfosFicheFrais['libEtat'];
    $lesTotauxFicheFrais = $pdo->calculElementIntermediaire($leVisiteur,$leMois);
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    include("vues/v_listeVisiteur.php");
    include("vues/v_listeMois.php");
    include("vues/vCOM_listeFraisForfait.php");
    include("vues/vCOM_listeFraisHorsForfait.php");
}
?>
