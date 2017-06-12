<?php
include("vues/v2_sommaireADM.php");
$idVisiteur = $_SESSION['idVisiteur'];
if(!isset($_GET['action'])){
    $_GET['action'] = 'voirCatFraisForfait';
}
$action = $_GET['action'];
switch($action){
    case 'voirCatFraisForfait':{
        $lesCatFraisForfait = $pdo->getLesCatFraisForfait();
        include("vues/vADM_listeCatFraisForfait.php");
        break;
    }
    case 'ajouterCatFraisForfait':{
        include("vues/vADM_ajoutCatFraisForfait.php");
        break;
    }
    case 'modifierCatFraisForfait':{
        $lesCatFraisForfait = $pdo->getLesCatFraisForfait($_POST['idCatFraisForfait']);
        include("vues/vADM_modifCatFraisForfait.php");
        break;
    }
    case 'validerCreationCatFraisForfait':{
        $id      = strtoupper($_POST['idCatFraisForfait']);
        $libelle = $_POST['libelleCatFraisForfait'];
        $montant = $_POST['montantCatFraisForfait'];
        if ($_POST['montantCatFraisForfait'] != ""){
            $montant = formatageNbDecimal($_POST['montantCatFraisForfait']);
        }
        valideInfosCatFraisForfait($id,$libelle,$montant);
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
            include("vues/vADM_ajoutCatFraisForfait.php");
        }
        else{
            if (isset($id) && isset($libelle) && isset($montant) && !empty($id) && !empty($libelle) && !empty($montant)){
                $pdo->ajoutCatFraisForfait($id, $libelle, $montant);
            }
            header('Location: index.php?uc=gererCatFraisForfait&action=voirCatFraisForfait');
        }
        break;
    }
    case 'validerMajCatFraisForfait':{
        $lesCatFraisForfait = $pdo->getLesCatFraisForfait($_POST['idCatFraisForfait']);
        $id = $_POST['idCatFraisForfait'];
        $libelle = $_POST['libelleCatFraisForfait'];
        $montant = $_POST['montantCatFraisForfait'];
        valideInfosCatFraisForfait($id,$libelle,$montant);
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
            include("vues/vADM_modifCatFraisForfait.php");
        }
        else{
            if (isset($id) && isset($libelle) && isset($montant) && !empty($id) && !empty($libelle) && !empty($montant)){
                $pdo->majCatFraisForfait($id,$libelle,$montant);
            }
            header('Location: index.php?uc=gererCatFraisForfait&action=voirCatFraisForfait');
        }
        break;
    }
    case 'validerSuppressionCatFraisForfait':{
        $lesCatFraisForfait = $pdo->getLesCatFraisForfait();
        $catFraisForfait = $pdo->getLesCatFraisForfait($_POST['idCatFraisForfait']);
        $id = $_POST['idCatFraisForfait'];
        $libelle = $catFraisForfait[0]['libelleFF'];
        $result_rechercheDependance = $pdo->verifierDependanceCategorie($id,"fraisForfaitise");
        if ($result_rechercheDependance == true){
            ajouterErreur("Le frais forfait '$libelle' ne peut être supprimé car il est utilisé dans des fiches de frais");
            include("vues/v4_erreurs.php");
            include("vues/vADM_listeCatFraisForfait.php");
        }
        else{
            $pdo->supprimerCatFraisForfait($id);
            header('Location: index.php?uc=gererCatFraisForfait&action=voirCatFraisForfait');
        }
        break;
    }
}
?>
