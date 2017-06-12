<?php
include("vues/v2_sommaireADM.php");
$idVisiteur = $_SESSION['idVisiteur'];
if(!isset($_GET['action'])){
    $_GET['action'] = 'voirCptUtilisateur';
}
$action = $_GET['action'];
switch($action){
    case 'voirCptUtilisateur':{
        $lesCptUtilisateur = $pdo->getLesCptUtilisateur();
        include("vues/vADM_listeCptUtilisateur.php");
        break;
    }
    case 'ajouterCptUtilisateur':{
        $lesCatEmploye = $pdo->getLesDonnéesFixesForm("typeemploye","libelleTypeEmploye");
        $lesCatFraisKm = $pdo->getLesDonnéesFixesForm("typevehicule","libelleTypeVehicule");
        $localisation = $pdo->getLesDonnéesFixesForm("localisation","libelleVille");
        include("vues/vADM_ajoutCptUtilisateur.php");
        break;
    }
    case 'modifierCptUtilisateur':{
        $lesCptUtilisateur = $pdo->getLesCptUtilisateur($_POST['idUser']);
        $lesCatEmploye = $pdo->getLesDonnéesFixesForm("typeemploye","libelleTypeEmploye");
        $lesCatFraisKm = $pdo->getLesDonnéesFixesForm("typevehicule","libelleTypeVehicule");
        $localisation = $pdo->getLesDonnéesFixesForm("localisation","libelleVille");
        include("vues/vADM_modifCptUtilisateur.php");
        break;
    }
    case 'validerCreationCptUtilisateur':{
        $nom = $_POST['nomEmploye'];
        $prenom = $_POST['prenomEmploye'];
        $adresse = $_POST['adresseEmploye'];
        $localisation = $_POST['localisation'];
        $login = strtolower($_POST['loginEmploye']);
        $mdp = $_POST['mdpEmploye'];
        $dateEmbauche = $_POST['dateEmbaucheEmploye'];
        $typeEmploye = $_POST['typeEmploye'];
        $fraisKm = $_POST['fraisKm'];
        $etatCpt = $_POST['activationCptEmploye'];
        $dateCreationCpt = date("Y-m-d");
        $lesLogins = $pdo->getLesCptUtilisateur();
        foreach ($lesLogins as $unLogin){
            $liste_login[] = $unLogin['login'];
        }
        valideInfosCptUtilisateur($nom,$prenom,$adresse,$localisation,$login,$liste_login,$mdp,$dateEmbauche,$typeEmploye);
        if ($_POST['mdpEmploye'] != ""){
            $mdp = sha1(md5($_POST['mdpEmploye']));
        }
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
            $lesCatEmploye = $pdo->getLesDonnéesFixesForm("typeemploye","libelleTypeEmploye");
            $lesCatFraisKm = $pdo->getLesDonnéesFixesForm("typevehicule","libelleTypeVehicule");
            $localisation = $pdo->getLesDonnéesFixesForm("localisation","libelleVille");
            include("vues/vADM_ajoutCptUtilisateur.php");
        }
        else{
            if (isset($nom) && isset($prenom) && isset($adresse) && isset($login) && isset($mdp) && isset($dateEmbauche) && isset($typeEmploye) && isset($localisation) && isset($etatCpt)
                && !empty($nom) && !empty($prenom) && !empty($adresse) && !empty($login) && !empty($mdp) && !empty($dateEmbauche) && !empty($typeEmploye) && !empty($localisation)){
                $result = $pdo->ajoutCptUtilisateur($nom, $prenom, $adresse, $login, $mdp, $dateEmbauche, $typeEmploye, $fraisKm, $localisation, $etatCpt, $dateCreationCpt);
            }
            header('Location: index.php?uc=gererCptUtilisateur&action=voirCptUtilisateur');
        }
        break;
    }
    case 'validerMajCptUtilisateur':{
        $id = $_POST['idEmploye'];
        $nom = $_POST['nomEmploye'];
        $prenom = $_POST['prenomEmploye'];
        $adresse = $_POST['adresseEmploye'];
        $login = $_POST['loginEmploye'];
        $mdp = $_POST['mdpEmploye'];
        $dateEmbauche = $_POST['dateEmbaucheEmploye'];
        $typeEmploye = $_POST['typeEmploye'];
        $fraisKm = $_POST['fraisKm'];
        if ($_POST['fraisKm'] == 0){
            $fraisKm = "null";
        }
        $localisation = $_POST['localisation'];
        $etatCpt = $_POST['activationCptEmploye'];
        $dateMajCpt = date("Y-m-d");
        $lesLogins = $pdo->getLesCptUtilisateur();
        foreach ($lesLogins as $unLogin){
            $liste_login[] = $unLogin['login'];
        }
        valideInfosCptUtilisateur($nom,$prenom,$adresse,$localisation,$login,$liste_login,$mdp,$dateEmbauche,$typeEmploye,false);
        if ($_POST['mdpEmploye'] != ""){
            $mdp = sha1(md5($_POST['mdpEmploye']));
        }
        if (nbErreurs() != 0){
            include("vues/v4_erreurs.php");
            $lesCptUtilisateur = $pdo->getLesCptUtilisateur($id);
            $lesCatEmploye = $pdo->getLesDonnéesFixesForm("typeemploye","libelleTypeEmploye");
            $lesCatFraisKm = $pdo->getLesDonnéesFixesForm("typevehicule","libelleTypeVehicule");
            $localisation = $pdo->getLesDonnéesFixesForm("localisation","libelleVille");
            include("vues/vADM_modifCptUtilisateur.php");
        }
        else{
            if (isset($nom) && isset($prenom) && isset($adresse) && isset($login) && isset($dateEmbauche) && isset($typeEmploye) && isset($localisation) && isset($etatCpt)
            && !empty($nom) && !empty($prenom) && !empty($adresse) && !empty($login) && !empty($dateEmbauche) && !empty($typeEmploye) && !empty($localisation)){
                $result = $pdo->majCptUtilisateur($id,$nom, $prenom, $adresse, $login, $mdp, $dateEmbauche, $typeEmploye, $fraisKm, $localisation, $etatCpt, $dateMajCpt);
            }
            header('Location: index.php?uc=gererCptUtilisateur&action=voirCptUtilisateur');
        }
        break;
    }
    case 'validerSuppressionCptUtilisateur':{
        $lesCptUtilisateur = $pdo->getLesCptUtilisateur();
        $id = $_POST['idUser'];
        $result_rechercheDependance = $pdo->verifierDependanceCategorie($id,"employe");
        if ($result_rechercheDependance == true){
            ajouterErreur("L'utilisateur portant l'ID '$id' ne peut être supprimé car il est lié à des fiches de frais dans l'application");
            include("vues/v4_erreurs.php");
            include("vues/vADM_listeCptUtilisateur.php");
        }
        else{
            $pdo->supprimerCptUtilisateur($id);
            header('Location: index.php?uc=gererCptUtilisateur&action=voirCptUtilisateur');
        }
        break;
    }
}
?>
