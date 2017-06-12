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
    $_GET['action'] = 'selectionnerMois';
}
$action = $_GET['action'];
switch($action){
    case 'selectionnerMois':{
        $lesMois=$pdo->getLesMoisDisponibles($idVisiteur,$periodeActuelle);
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste, on demande toutes les clés, et on prend la première (les mois étant triés décroissants)
        $lesCles = array_keys($lesMois);
        if (!empty($lesCles)){
            $moisASelectionner = $lesCles[0];
        }
        else{
            $moisASelectionner = "";
        }
        include("vues/v_listeMois.php");
        break;
    }
    case 'voirEtatFrais':{
        if (isset($_GET['resultTelechargement'])){
            if ($_GET['resultTelechargement'] == 0) {
                ajouterErreur("Erreur de téléchargement du fichier (taille maxi = 2Mo / types autorisés = pdf, png, jpeg ou jpg)");
                include("vues/v4_erreurs.php");
            }
        }
        if (isset($_POST['lstMois'])){
            $leMois = $_POST['lstMois'];
        }
        else {
                /* @var $leMois type */ 
                $leMois = @$_GET['mois'];
        }
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur,$periodeActuelle);
        include("vues/v_listeMois.php");
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur,$leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
        $lesJustificatifs = $pdo->getLesJustificatifs($idVisiteur);
        $numAnnee = substr($leMois,0,4);
        $numMois = substr($leMois,4,2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbrJustificatif = $lesInfosFicheFrais['nbrJustificatif'];
        $dateModif = $lesInfosFicheFrais['dateModif'];
        $dateModif = dateAnglaisVersFrancais($dateModif);
        $nomDuComptable = $lesInfosFicheFrais['nomComptable'];
        $prenomDuComptable = $lesInfosFicheFrais['prenomComptable'];
        include("vues/vVIS_etatFicheFrais.php");
        break;
    }
    case 'telechargerJustificatifFrais':{
        $resultTelechargement = FALSE;
        $moisSelectionne = $_POST['mois'];
        if (isset($_FILES) && !empty($_FILES)){
            $nomFichier = $_FILES['justificatif']['name'];
            $emplacementFichier = $_FILES['justificatif']['tmp_name'];
            $codeErreurFichier = $_FILES['justificatif']['error'];
            $tailleFichier = $_FILES['justificatif']['size'];
            $tailleMaxFichier = $_POST['MAX_FILE_SIZE'];
            $idFHF = $_POST['idFraisHorsForfait'];
            valideJustificatif($nomFichier,$tailleFichier,$tailleMaxFichier,$codeErreurFichier);
            if (nbErreurs() == 0){
                if ($codeErreurFichier == 0){
                    $dateTelechargement = date('Y-m-d');
                    $cheminAccess = renommageNomFichier($nomFichier, $mois, $idVisiteur, $idFHF);
                    $result = enregistrementFichier($cheminAccess, $emplacementFichier);
                    if ($result){
                        $pdo->creeNouveauJustificatif($cheminAccess, $dateTelechargement, $idVisiteur, $moisSelectionne, $idFHF);
                        $resultTelechargement = TRUE;
                    }
                }
            }
        }
        $resultTelechargement == FALSE ? $resultTelechargement = 0 : $resultTelechargement = 1; //Envoi d'un paramètre pour indiquer sur page suivante si le téléchargement s'est bien effectué ou non
        header("Location: index.php?uc=etatFrais&action=voirEtatFrais&resultTelechargement=$resultTelechargement&mois=$moisSelectionne");
        break;
    }
    case 'supprimerJustificatif':{
        $idJustificatif = $_POST['idJustificatif'];
        $moisSelectionne = $_POST['mois'];
        $pdo->supprimerJustificatif($idJustificatif,$idVisiteur,$mois);
        header("Location: index.php?uc=etatFrais&action=voirEtatFrais&mois=$moisSelectionne");
        break;
    }
}
?>
