<?php
include("vues/v2_sommaireADM.php");
$idVisiteur = $_SESSION['idVisiteur'];
if(!isset($_GET['action'])){
    $_GET['action'] = 'voirCatEmploye';
}
$action = $_GET['action'];
switch($action){
    case 'voirCatEmploye':{
        $lesCatEmploye = $pdo->getLesCatEmploye();
        include("vues/vADM_listeCatEmploye.php");
        break;
    }
    case 'ajouterCatEmploye':{
        include("vues/vADM_ajoutCatEmploye.php");
        break;
    }
    case 'modifierCatEmploye':{
        $lesCatEmploye = $pdo->getLesCatEmploye($_POST['idCatEmploye']);
        include("vues/vADM_modifCatEmploye.php");
        break;
    }
    case 'validerCreationCatEmploye':{
        $id = strtoupper($_POST['idCatEmploye']);
        $libelle = $_POST['libelleCatEmploye'];
        valideInfosCatEmploye($id,$libelle);
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
            include("vues/vADM_ajoutCatEmploye.php");
        }
        else{
            if (isset($id) && isset($libelle) && !empty($id) && !empty($libelle)){
                $pdo->ajoutCatEmploye($id, $libelle);
            }
            header('Location: index.php?uc=gererCatEmploye&action=voirCatEmploye');
        }
        break;
    }
    case 'validerMajCatEmploye':{
        $lesCatEmploye = $pdo->getLesCatEmploye($_POST['idCatEmploye']);
        $id = strtoupper($_POST['idCatEmploye']);
        $libelle = $_POST['libelleCatEmploye'];
        valideInfosCatEmploye($id,$libelle);
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
            include("vues/vADM_modifCatEmploye.php");
        }
        else{
            if (isset($id) && isset($libelle) && !empty($id) && !empty($libelle)){
                $pdo->majCatEmploye($id,$libelle);
            }
            header('Location: index.php?uc=gererCatEmploye&action=voirCatEmploye');
        }
        break;
    }
    case 'validerSuppressionCatEmploye':{
        $lesCatEmploye = $pdo->getLesCatEmploye();
        $catEmploye = $pdo->getLesCatEmploye($_POST['idCatEmploye']);
        $id = $_POST['idCatEmploye'];
        $libelle = $catEmploye[0]['libelleTypeEmploye'];
        $result_rechercheDependance = $pdo->verifierDependanceCategorie($id,"typeEmploye");
        if ($result_rechercheDependance == true){
            ajouterErreur("La catégorie '$libelle' ne peut être supprimée car elle est utilisée");
            include("vues/v4_erreurs.php");
            include("vues/vADM_listeCatEmploye.php");
        }
        else{
            $pdo->supprimerCatEmploye($id);
            header('Location: index.php?uc=gererCatEmploye&action=voirCatEmploye');
        }
        break;
    }
}
?>
