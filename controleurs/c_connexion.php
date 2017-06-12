<?php
if(!isset($_GET['action'])){
	$_GET['action'] = 'demandeConnexion';       
}

$action = $_GET['action'];
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_POST['login'];
		$mdp = sha1(md5($_POST['mdp']));
		$employe = $pdo->getInfosEmploye($login,$mdp);
		if(!is_array($employe)){
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v4_erreurs.php");
			include("vues/v_connexion.php");
		}
		else{
			$id = $employe['idEmploye'];
			$nom =  $employe['nom'];
			$prenom = $employe['prenom'];
                        $type = $employe['idTypeEmploye'];
			connecter($id,$nom,$prenom,$type);
			switch($type){
                            case 'VIS':{
                                include("vues/v2_sommaireVIS.php");
                                include("vues/v3_accueilVIS.php");
                                break;
                            }
                            case 'COM':{
                                include("vues/v2_sommaireCOM.php");
                                include("vues/v3_accueilCOM.php");
                                break;
                            }
                            case 'ADM':{
                                include("vues/v2_sommaireADM.php");
                                include("vues/v3_accueilADM.php");
                                break;
                            }
                        }
                }   
		break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>