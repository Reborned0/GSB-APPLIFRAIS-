<?php
include("vues/v2_sommaireVIS.php");
$idVisiteur = $_SESSION['idVisiteur'];
$typeEmploye = $_SESSION['type'];
$mois = getMois(date("d/m/Y"));
$dateActuelle = date('Ymd');
$jourActuel = substr($dateActuelle,6,2);
$moisActuel = substr($dateActuelle,4,2);
$anneeActuel = substr($dateActuelle,0,4);
$periodeActuelle = $anneeActuel . $moisActuel;
if(!isset($_GET['action'])){
    $_GET['action'] = 'saisirFrais';
}
$action = $_GET['action'];
switch($action){
    case 'saisirFrais':{
        if($pdo->estPremierFraisMois($idVisiteur,$mois)){
            $pdo->creeNouvellesLignesFrais($idVisiteur,$mois);
        }
            break;
        }
    case 'validerMajFraisForfait':{ /*Enregistre les frais forfait*/
        $lesFrais = $_POST['lesFrais'];
        if(lesQteFraisValides($lesFrais)){
            $pdo->majFraisForfait($idVisiteur,$mois,$lesFrais);
        }
        else{
            ajouterErreur("Les valeurs des frais doivent être numériques");
            include("vues/v4_erreurs.php");
        }
        break;
    }
    case 'validerCreationFrais':{ /*Enregistre les frais hors forfait + justificatif si téléchargé en même temps que création frais*/
        $dateFrais = $_POST['dateFrais'];
        $libelle = $_POST['libelle'];
        $montant = $_POST['montant'];
        if ($_POST['montant'] != ""){
            $montant = formatageNbDecimal($_POST['montant']);
        }
        valideInfosFrais($dateFrais,$libelle,$montant);
        if (isset($_FILES) && !empty($_FILES)){
            $nomFichier = $_FILES['justificatif']['name'];
            $emplacementFichier = $_FILES['justificatif']['tmp_name'];
            $codeErreurFichier = $_FILES['justificatif']['error'];
            $tailleFichier = $_FILES['justificatif']['size'];
            $tailleMaxFichier = $_POST['MAX_FILE_SIZE'];
            if ($codeErreurFichier != 4){
                valideJustificatif($nomFichier,$tailleFichier,$tailleMaxFichier,$codeErreurFichier);
            }
        }
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
        }
        else{
            $pdo->creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$dateFrais,$montant);
            /* Traitement du fichier téléchargé */
            if ($codeErreurFichier == 0){
                $dateTelechargement = date('Y-m-d');
                $idFHF = $pdo->getLesFraisHorsForfait($idVisiteur,$mois,1);
                $cheminAccess = renommageNomFichier($nomFichier,$mois,$idVisiteur,$idFHF);
                $result = enregistrementFichier($cheminAccess,$emplacementFichier);
                if ($result){
                    $pdo->creeNouveauJustificatif($cheminAccess,$dateTelechargement,$idVisiteur,$mois);
                }
            }
        }
        break;
    }
    case 'telechargerJustificatifFrais':{
        if (isset($_FILES) && !empty($_FILES)){
            $nomFichier = $_FILES['justificatif']['name'];
            $emplacementFichier = $_FILES['justificatif']['tmp_name'];
            $codeErreurFichier = $_FILES['justificatif']['error'];
            $tailleFichier = $_FILES['justificatif']['size'];
            $tailleMaxFichier = $_POST['MAX_FILE_SIZE'];
            $idFHF = $_POST['idFraisHorsForfait'];
            if ($codeErreurFichier != 4) {
                valideJustificatif($nomFichier, $tailleFichier, $tailleMaxFichier, $codeErreurFichier);
            }
            if (nbErreurs() != 0){
                include("vues/v4_erreurs.php");
            }
            else{
                if ($codeErreurFichier == 0){
                    $dateTelechargement = date('Y-m-d');
                    $cheminAccess = renommageNomFichier($nomFichier, $mois, $idVisiteur, $idFHF);
                    $result = enregistrementFichier($cheminAccess, $emplacementFichier);
                    if ($result) {
                        $pdo->creeNouveauJustificatif($cheminAccess,$dateTelechargement,$idVisiteur,$mois,$idFHF);
                    }
                }
            }
        }
        break;
    }
    case 'supprimerFrais':{
        $idFrais = $_POST['idFrais'];
        $nbJustificatifsPourCeFrais = $pdo->getnbrJustificatifPourUnFraisHorsForfait($idFrais);
        if ($nbJustificatifsPourCeFrais == 0){
            $result = $pdo->supprimerFraisHorsForfait($idFrais,$idVisiteur,$mois);
        }
        else{
            ajouterErreur("Supprimez d'abord tous les justificatifs de ce frais pour pouvoir le supprimer");
            include("vues/v4_erreurs.php");
        }
        break;
    }
    case 'supprimerJustificatif':{
        $idJustificatif = $_POST['idJustificatif'];
        $pdo->supprimerJustificatif($idJustificatif,$idVisiteur,$mois);
        break;
    }
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur,$mois);
$lesFraisForfaitKm = $pdo->getLesCptUtilisateur($idVisiteur); /*Permet de récupérer le frais KM forfait propre à chaque visiteur en fonction de son type de véhicule (diesel ou essence / 4CV, 5CV, 6CV, ...) */
$lesJustificatifs = $pdo->getLesJustificatifs($idVisiteur);
$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$mois);
$dateModifFicheFrais = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
$etatFicheFrais = $lesInfosFicheFrais['libEtat'];
$nbJustificatifs = $lesInfosFicheFrais['nbrJustificatifs'];
$lesTotauxFicheFrais = $pdo->calculElementIntermediaire($idVisiteur,$mois);
include("vues/vVIS_listeFraisForfait.php");
include("vues/vVIS_listeFraisHorsForfait.php");
?>

