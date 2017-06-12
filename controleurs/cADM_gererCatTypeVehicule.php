<?php
include("vues/v2_sommaireADM.php");
$idVisiteur = $_SESSION['idVisiteur'];
if(!isset($_GET['action'])){
    $_GET['action'] = 'voirCatFraisKm';
}
$action = $_GET['action'];
switch($action){
    case 'voirCatFraisKm':{
        $lesCatFraisKm = $pdo->getLesCatFraisKm();
        include("vues/vADM_listeCatFraisKm.php");
        break;
    }
    case 'ajouterCatFraisKm':{
        include("vues/vADM_ajoutCatFraisKm.php");
        break;
    }
    case 'modifierCatFraisKm':{
        $lesCatFraisKm = $pdo->getLesCatFraisKm($_POST['idCatFraisKm']);
        include("vues/vADM_modifCatFraisKm.php");
        break;
    }
    case 'validerCreationCatFraisKm':{
        $libelle = $_POST['libelleCatFraisKm'];
        valideInfosCatFraisKm($libelle);
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
            include("vues/vADM_ajoutCatFraisKm.php");
        }
        else{
            if (isset($libelle) && !empty($libelle)){
                $pdo->ajoutCatFraisKm($libelle);
            }
            header('Location: index.php?uc=gererCatFraisKm&action=voirCatFraisKm');
        }
        break;
    }
    case 'validerMajCatFraisKm':{
        $lesCatFraisKm = $pdo->getLesCatFraisKm($_POST['idCatFraisKm']);
        $id = $_POST['idCatFraisKm'];
        $libelle = $_POST['libelleCatFraisKm'];
        valideInfosCatFraisKm($libelle);
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
            include("vues/vADM_modifCatFraisKm.php");
        }
        else{
            if (isset($id) && isset($libelle) && !empty($id) && !empty($libelle)){
                $pdo->majCatFraisKm($id,$libelle);
            }
            header('Location: index.php?uc=gererCatFraisKm&action=voirCatFraisKm');
        }
        break;
    }
    case 'validerSuppressionCatFraisKm':{
        $lesCatFraisKm = $pdo->getLesCatFraisKm();
        $catFraisKm = $pdo->getLesCatFraisKm($_POST['idCatFraisKm']);
        $id = $_POST['idCatFraisKm'];
        $libelle = $catFraisKm[0]['libelleTypeVehicule'];
        $result_rechercheDependance = $pdo->verifierDependanceCategorie($id,"typeVehicule");
        if ($result_rechercheDependance == true){
            ajouterErreur("La catégorie '$libelle' ne peut être supprimée car elle est utilisée");
            include("vues/v4_erreurs.php");
            include("vues/vADM_listeCatFraisKm.php");
        }
        else{
            $pdo->supprimerCatFraisKm($id);
            header('Location: index.php?uc=gererCatFraisKm&action=voirCatFraisKm');
        }
        break;
    }
}
?>
