<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Souvannarith CHEA
 * @version    2.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
        private static $port=''; /*port=3306*/
      	private static $bdd='dbname=u466434734_gsbfr';   		
      	private static $user='u466434734_testa' ;    		
      	private static $mdp='' ;	
	private static $monPdo;
	private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	//PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
	//	PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	
            try{
                if (PdoGsb::$port == ''){
		PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
		}
		else{
                    PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$port.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
		}
                PdoGsb::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
            }
            catch (PDOException $erreur){
                echo "<p>Erreur de connexion à la BDD : ".$erreur->getMessage()."</p>\n";
            }
        }
        
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Clôture toutes les fiches de frais du mois précédent avant vérification par les comptables
 */
    public function clotureLesFichesFrais(){
        $periode_actuelle = date('Ym');
        $req = "update fichefrais set fichefrais.idEtatFiche = 'CL', dateModifFicheF = now() where fichefrais.idEtatFiche = 'CR' AND fichefrais.periodeConcernee != '$periode_actuelle'";
        PdoGsb::$monPdo->exec($req);
    }
/**
 * Met en paiement toutes les fiches de frais du mois précédent qui ont été validées
 */
    public function metEnPaiementLesFichesFrais(){
        $periode_actuelle = date('Ym');
        $req = "update fichefrais set fichefrais.idEtatFiche = 'RB', dateModifFicheF = now() where fichefrais.idEtatFiche = 'VA' AND fichefrais.periodeConcernee != '$periode_actuelle'";
        PdoGsb::$monPdo->exec($req);
    }

/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosEmploye($login, $mdp){
		$req = "select employe.idEmploye as idEmploye,
                employe.nom as nom,
                employe.prenom as prenom,
                employe.adresse as adresse,
                employe.login as login,
                employe.mdp as mdp,
                employe.dateEmbauche as dateEmbauche,
                employe.dateModifFicheE as dateModifFicheE,
                employe.cptActif as cptActif,
                employe.idTypeEmploye as idTypeEmploye,
                employe.idLocalisation as idLocalisation,
                employe.idTypeVehicule as idFraisForfaitKm
                from employe
                inner join typeemploye on employe.idTypeEmploye = typeemploye.idTypeEmploye
                inner join localisation on employe.idLocalisation = localisation.idLocalisation
                left join typevehicule on employe.idTypeVehicule = typevehicule.idTypeVehicule
                where employe.login='$login' and employe.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois,$limit=NULL){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.emp_idemploye ='$idVisiteur' 
		and lignefraishorsforfait.periodeConcernee = '$mois' ";	
                if ($limit != NULL){
                    $req .= "order by idLigneFHF desc limit 1";
                }
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll(PDO::FETCH_ASSOC);
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['dateLigneFHF'];
			$lesLignes[$i]['dateLigneFHF'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne sous forme d'un tableau associatif tous les justificatifs concernées par les deux arguments

 * @param $idVisiteur
 * @return tous les champs des lignes de justificatif sous la forme d'un tableau associatif
 */
    public function getLesJustificatifs($idVisiteur){
        $req = "select * from justificatiflignefhf
                inner join lignefraishorsforfait on lignefraishorsforfait.idLigneFHF = justificatiflignefhf.idLigneFHF
                where lignefraishorsforfait.emp_idEmploye ='$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll(PDO::FETCH_ASSOC);
        return $lesLignes;
    }
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select lignefraisforfaitise.idFraisForfaitise as idfrais, lignefraisforfaitise.emp_idEmploye as idEmploye, 
		lignefraisforfaitise.periodeConcernee as mois, lignefraisforfaitise.qteLigneFF as quantite, fraisforfaitise.libelleFF as libelleFrais,
                fraisforfaitise.idFraisForfaitise as idFF, fraisforfaitise.montantFF as montantFF 
                from lignefraisforfaitise
                inner join fraisforfaitise on fraisforfaitise.idFraisForfaitise = lignefraisforfaitise.idFraisForfaitise
                inner join fichefrais on fichefrais.emp_idEmploye = lignefraisforfaitise.emp_idEmploye AND fichefrais.periodeConcernee = lignefraisforfaitise.periodeConcernee
		where lignefraisforfaitise.emp_idEmploye ='$idVisiteur' and lignefraisforfaitise.periodeConcernee ='$mois' 
		order by lignefraisforfaitise.idFraisForfaitise";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll(PDO::FETCH_ASSOC);
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
    public function getLesIdFrais(){
        $req = "select fraisforfaitise.idFraisForfaitise as idfrais from fraisforfaitise where fraisforfaitise.idFraisForfaitise not like 'KM%' order by fraisforfaitise.idFraisForfaitise";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }
/**
 * Retourne tous les id de la table FraisForfait

 * @return un tableau associatif
 */
    public function getIdFraisKm($libFraisKm){
        $req = "select fraisforfaitise.idFraisForfaitise as idfrais from fraisforfaitise where fraisforfaitise.idFraisForfaitise like 'KM%' and fraisforfaitise.libelleFF like '%$libFraisKm%'";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll(PDO::FETCH_ASSOC);
        return $lesLignes;
    }
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfaitise set lignefraisforfaitise.qteLigneFF = $qte
			where lignefraisforfaitise.emp_idEmploye = '$idVisiteur' and lignefraisforfaitise.periodeConcernee = '$mois' 
			and lignefraisforfaitise.idFraisForfaitise = '$unIdFrais'";
			$result = PdoGsb::$monPdo->exec($req);
		}
		return $result;
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
        
/** Calcul des éléments interédiaires de la fiche de frais

 * @param $idVisiteur
 * @param $mois
 * @return array
 */
    public function calculElementIntermediaire($idVisiteur,$mois){
        $fraisForfait = $this->getLesFraisForfait($idVisiteur,$mois);
        $totaux = array();
        $totalGlobalFF = 0;
        $totalGlobalFHF = 0;
        foreach ($fraisForfait as $detailFF){
            $idLigneFraisF = $detailFF['idfrais'];
            $qteLigneFraisF = $detailFF['quantite'];
            $montantFraisF = $detailFF['montantFF'];

            $total = number_format(($qteLigneFraisF * $montantFraisF),2,".","");
            $totalGlobalFF += $total;
            $totaux += array($idLigneFraisF => $total);
        }
        $totaux += array('totalGlobalFF' => number_format($totalGlobalFF,2,".",""));

        $fraisHorsForfait = $this->getLesFraisHorsForfait($idVisiteur,$mois);
        foreach ($fraisHorsForfait as $detailFHF){
            if ($detailFHF['statutLigneFHF_refuse'] == null){
                $totalGlobalFHF += $detailFHF['montantLigneFHF'];
            }
        }
        $totaux += array('totalGlobalFHF' => number_format($totalGlobalFHF,2,".",""));
        $totaux += array('totalGlobalFiche' => (number_format(($totalGlobalFF + $totalGlobalFHF),2,".","")));
        return $totaux;
    }
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.periodeConcernee = '$mois' and fichefrais.emp_idEmploye = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch(PDO::FETCH_ASSOC);
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(fichefrais.periodeConcernee) as dernierMois from fichefrais where fichefrais.emp_idEmploye = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
                /** Création d'une nouvelle fiche de frais pour le mois en cours */
		$req = "insert into fichefrais(emp_idEmploye,periodeConcernee,idEtatFiche,idEmploye,montantValide,dateModifFicheF,nbrJustificatif) 
		values('$idVisiteur','$mois','CR', NULL,0, now(),0)";
		PdoGsb::$monPdo->exec($req);
                /** Création des nouvelles lignes de frais forfaitisés pour la nouvelle fiche de frais créée avec initialisation des quantités à 0 (HORS FRAIS KM traités ci-après) */
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfaitise(emp_idEmploye,periodeConcernee,idFraisForfaitise,qteLigneFF) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
/** Création d'une nouvelle ligne de frais Km forfaitisé pour la nouvelle fiche de frais créée avec initialisation de la quantité à 0.
         *  Frais Km traités à part des autres frais forfaitisés car vérification du type de voiture qu'a l'employé afin de faire correspondre au bon montant de frais Km. */
        $infosEmploye = $this->getLesCptUtilisateur($idVisiteur);
        $fraisKmEmploye = $infosEmploye[0]['libelleTypeVehicule'];
        $idFraisKm = $this->getIdFraisKm($fraisKmEmploye);
        $idFraisKmEmploye = $idFraisKm[0]['idfrais'];
        $req = "insert into lignefraisforfaitise(emp_idEmploye,periodeConcernee,idFraisForfaitise,qteLigneFF)
                values('$idVisiteur','$mois','$idFraisKmEmploye',0)";
        PdoGsb::$monPdo->exec($req);
    }
/** Reporte un frais hors forfait refusé sur la fiche de frais du mois en cours

 * @param $idFHFReporte
 * @param $mois
 * @param $idVisiteur
 * @param $moisFichePrecedente
 */
    public function reporterFraisHorsForfait($idFHFReporte,$mois,$idVisiteur,$moisFichePrecedente){
        $req = "update lignefraishorsforfait set lignefraishorsforfait.periodeConcernee='$mois' where lignefraishorsforfait.idLigneFHF='$idFHFReporte'";
        $result = PdoGsb::$monPdo->exec($req);
        if ($result){
            $this->majNbrJustificatifFicheFrais($idVisiteur,$moisFichePrecedente);
            $this->majNbrJustificatifFicheFrais($idVisiteur,$mois);
        }
        return $result;
    }  
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
                $libelle = addslashes(htmlspecialchars($libelle));
		$req = "insert into lignefraishorsforfait(emp_idEmploye, periodeConcernee, libelleLigneFHF, montantLigneFHF, dateLigneFHF)
		values('$idVisiteur','$mois','$libelle','$montant','$dateFr')";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais,$idVisiteur,$mois){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.idLigneFHF ='$idFrais '";
                $result = PdoGsb::$monPdo->exec($req);
		if ($result){
                    $this->majNbrJustificatifFicheFrais($idVisiteur,$mois);
                }
	}
/**
 * Reporte un frais hors forfait sur fiche de frais du mois suivant (création de la fiche de frais si elle n'existe pas déjà)

 * @param $idFrais
 * @param $leVisiteur
 * @param $mois
 * @param $leMois
 */
    public function reporterFraisHorsForfaitSurFicheFraisSuivante($idFrais,$leVisiteur,$mois,$leMois=NULL){
        $result = null;
        if ($this->estPremierFraisMois($leVisiteur,$mois)){
            $this->creeNouvellesLignesFrais($leVisiteur,$mois,$idFrais,$leMois);
            $result = $this->reporterFraisHorsForfait($idFrais,$mois,$leVisiteur,$leMois);
        }
        else{
            $result = $this->reporterFraisHorsForfait($idFrais,$mois,$leVisiteur,$leMois);
        }
        return $result;
    }
/**
 * Refus/suppression du frais hors forfait par le comptable donc mise à jour du libellé avec la mention "REFUSE" en début de libellé

 * @param $idFrais
 */
    public function refuserFraisHorsForfait($idFrais,$leVisiteur,$mois,$leMois=NULL){
        $infosFraisHF = $this->getLesInfosFraisHF($idFrais);
        $libelleFHF = $infosFraisHF['libelleLigneFHF'];
        $req = "update lignefraishorsforfait set lignefraishorsforfait.libelleLigneFHF = 'REFUSE - $libelleFHF', lignefraishorsforfait.statutLigneFHF_refuse = 1
                where lignefraishorsforfait.idLigneFHF = '$idFrais'";
        $result = PdoGsb::$monPdo->exec($req);
        return $result;
    }
/** Retourne le nombre de justificatif qui correspond à un frais hors forfait

 * @param $idFHF
 * @return le nombre de justificatif
 */
    public function getnbrJustificatifPourUnFraisHorsForfait($idFHF){
        $req = "select count(*) as nbrJustificatif from justificatiflignefhf
                where justificatiflignefhf.idLigneFHF = '$idFHF'";
        $res = PdoGsb::$monPdo->query($req);
        $nbJustificatif = $res->fetch(PDO::FETCH_ASSOC);
        $nbJustificatif = (int) $nbJustificatif['nbrJustificatif'];
        return $nbJustificatif;
    }
/** Retourne le nombre de justificatif qui correspond à une fiche de frais pour un visiteur et un mois donné

 * @param $idVisiteur
 * @param $mois
 * @return un tableau associatif
 */
    public function getnbrJustificatifTotal($idVisiteur,$mois){
        $req = "select count(*) as nbrJustificatif from justificatiflignefhf
                inner join lignefraishorsforfait on lignefraishorsforfait.idLigneFHF = justificatiflignefhf.idLigneFHF
                where lignefraishorsforfait.emp_idEmploye = '$idVisiteur' and lignefraishorsforfait.periodeConcernee = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $nbJustificatif = $res->fetch(PDO::FETCH_ASSOC);
        return $nbJustificatif;
    }
/** Met à jour le nombre de justificatif d'une fiche frais de la table fichefrais qui a pour ID l'idVisiteur et le mois passés en paramètres

 * @param $idVisiteur
 * @param $mois
 */
    public function majNbrJustificatifFicheFrais($idVisiteur,$mois){
        $nbrJustificatif = $this->getnbrJustificatifTotal($idVisiteur,$mois);
        $nbJustificatif = $nbrJustificatif['nbrJustificatif'];
        $req = "update fichefrais set fichefrais.nbrJustificatif = '$nbJustificatif'
                where fichefrais.emp_idEmploye = '$idVisiteur' and fichefrais.periodeConcernee = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }
/** Crée une nouvelle ligne justificatif à partir des informations fournies en paramètre

 * @param $cheminAcces
 * @param $dateTelechargement
 * @param null $idVisiteur
 * @param null $mois
 * @param null $idFraisHorsForfait
 */
    public function creeNouveauJustificatif($cheminAcces,$dateTelechargement,$idVisiteur=NULL,$mois=NULL,$idFraisHorsForfait=NULL){
        if ($idFraisHorsForfait != NULL){
            $idFHF = $idFraisHorsForfait;
        }
        else{
            $idFHF = $this->getLesFraisHorsForfait($idVisiteur,$mois,1);
            $idFHF = $idFHF[0]['idLigneFHF'];
        }
        $req = "insert into justificatiflignefhf(idLigneFHF, lienJustificatif, dateUpload) values('$idFHF','$cheminAcces','$dateTelechargement')";
        $result = PdoGsb::$monPdo->exec($req);
        if ($result){
            $this->majNbrJustificatifFicheFrais($idVisiteur,$mois);
        }
    }
/** Supprime le justificatif dont l'id est passé en argument

 * @param $idJustificatif
 */
    public function supprimerJustificatif($idJustificatif,$idVisiteur,$mois){
        $req = "delete from justificatiflignefhf where justificatiflignefhf.idJustificatif =$idJustificatif ";
        $result = PdoGsb::$monPdo->exec($req);
        if ($result){
            $this->majNbrJustificatifFicheFrais($idVisiteur,$mois);
        }
    }
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur,$periodeActuelle){
		$req = "select fichefrais.periodeConcernee as mois from fichefrais where fichefrais.emp_idEmploye ='$idVisiteur' and fichefrais.periodeconcernee != '$periodeActuelle'
		order by fichefrais.periodeConcernee desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numeroAnnee =substr( $mois,0,4);
			$numeroMois =substr( $mois,4,2);
			$lesMois["$mois"]=array("mois"=>"$mois",
                                                "numAnnee"  => "$numeroAnnee",
                                                "numMois"  => "$numeroMois");
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les mois pour lesquels un visiteur a une fiche de frais clôturée

 * @param $idVisiteur
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant
 */
    public function getLesMoisFicheFraisCloturee($idVisiteur){
        $req = "select fichefrais.periodeConcernee as mois from fichefrais where fichefrais.emp_idEmploye = '$idVisiteur' and fichefrais.idEtatFiche = 'CL' order by fichefrais.periodeConcernee desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois =array();
        $laLigne = $res->fetch();
        while($laLigne != null)	{
            $mois = $laLigne['mois'];
            $numeroAnnee =substr( $mois,0,4);
            $numeroMois =substr( $mois,4,2);
            $lesMois["$mois"]=array("mois"=>"$mois",
                                    "numAnnee"  => "$numeroAnnee",
                                    "numMois"  => "$numeroMois");
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }
/**
 * Retourne les mois pour lesquels un visiteur a une fiche de frais clôturée

 * @param $idVisiteur
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant
 */
    public function getLesMoisFicheFraisValidee($idVisiteur){
        $req = "select fichefrais.periodeConcernee as mois from fichefrais where fichefrais.emp_idEmploye = '$idVisiteur' and (fichefrais.idEtatFiche = 'VA' or fichefrais.idEtatFiche = 'RB') order by fichefrais.periodeConcernee desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois =array();
        $laLigne = $res->fetch();
        while($laLigne != null)	{
            $mois = $laLigne['mois'];
            $numeroAnnee =substr( $mois,0,4);
            $numeroMois =substr( $mois,4,2);
            $lesMois["$mois"]=array("mois"=>"$mois",
                "numAnnee"  => "$numeroAnnee",
                "numMois"  => "$numeroMois");
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select fichefrais.idEtatFiche as idEtat, fichefrais.dateModifFicheF as dateModif, fichefrais.nbrJustificatif as nbrJustificatifs, 
			fichefrais.montantValide as montantValide, etatfiche.libelleEtatFiche as libEtat, employe.nom as nomComptable, employe.prenom as prenomComptable
                        from  fichefrais inner join etatfiche on etatfiche.idEtatFiche = fichefrais.idEtatFiche 
                        left join employe on employe.idEmploye = fichefrais.idEmploye
			where fichefrais.emp_idEmploye ='$idVisiteur' and fichefrais.periodeConcernee = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch(PDO::FETCH_ASSOC);
		return $laLigne;
	}
/**
 * Retourne les informations d'un frais hors forfait

 * @param $idFHF
 * @return un tableau associatif
 */
    public function getLesInfosFraisHF($idFHF){
        $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idLigneFHF ='$idFHF'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch(PDO::FETCH_ASSOC);
        return $laLigne;
    }
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat,$montant_valide = 0,$idComptable = 0){
		$req = "update fichefrais set idEtatFiche = '$etat', dateModifFichierF = now() 
		where fichefrais.emp_idEmploye ='$idVisiteur' and fichefrais.periodeConcernee = '$mois'";
		PdoGsb::$monPdo->exec($req);
                if ($etat == 'VA'){
                    $req = "update fichefrais set montantValide = $montant_valide, idEmploye = $idComptable where fichefrais.emp_idEmploye ='$idVisiteur' and fichefrais.periodeConcernee ='$mois'";
                    PdoGsb::$monPdo->exec($req);
                }
	}
/**
* Retourne les catégories "Type employé" existantes dans la BDD

* @return un tableau associatif de clé idTypeEmploye et de valeur libelleTypeEmploye
*/
    public function getLesCatEmploye($idCatEmploye=NULL){
        $req = "select typeemploye.idTypeEmploye as idTypeEmploye, typeemploye.libelleTypeEmploye as libelleTypeEmploye from typeemploye";
        if(!is_null($idCatEmploye)){
            $req .= " where typeemploye.idTypeEmploye = '$idCatEmploye'";
}
    $res = PdoGsb::$monPdo->query($req);
    $laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
    return $laLigne;
}
/**
* Création d'un nouveau tuple dans la table typeemploye

* @param $idCatEmploye
* @param $libelleCatEmploye
*/
    public function ajoutCatEmploye($idCatEmploye, $libelleCatEmploye){
        $libelleCatEmploye = addslashes(htmlspecialchars($libelleCatEmploye));
        $req = "insert into typeemploye(idTypeEmploye,libelleTypeEmploye) values('$idCatEmploye','$libelleCatEmploye')";
        PdoGsb::$monPdo->exec($req);
}
/**
* Met à jour la table typeemploye

* @param $libCatEmploye tableau associatif de clé idTypeEmploye et de valeur le libellé saisi dans le formulaire de modification
*/
    public function majCatEmploye($idCatEmploye,$libCatEmploye){
        $libCatEmploye = addslashes(htmlspecialchars($libCatEmploye));
            $req = "update typeemploye set typeemploye.libelleTypeEmploye = '$libCatEmploye'
        where typeemploye.idTypeEmploye = '$idCatEmploye'";
        PdoGsb::$monPdo->exec($req);
}
/**
* Suppression d'un tuple dans la table typeemploye

* @param $idCatEmploye
*/
    public function supprimerCatEmploye($idCatEmploye){
        $req = "delete from typeemploye where typeemploye.idTypeEmploye = '$idCatEmploye'";
    PdoGsb::$monPdo->exec($req);
}
/**
* Retourne les catégories "Type frais forfaitisé Km" existantes dans la BDD

* @return un tableau associatif de clé idTypeVehicule et de valeurs libelleTypeVehicule
*/
    public function getLesCatFraisKm($idCatFraisKm=NULL){
        $req = "select typevehicule.idTypeVehicule as idTypeVehicule, typevehicule.libelleTypeVehicule as libelleTypeVehicule from typevehicule";
    if(!is_null($idCatFraisKm)){
        $req .= " where typevehicule.idTypeVehicule = '$idCatFraisKm'";
}
    $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
    return $laLigne;
}
/**
* Création d'un nouveau tuple dans la table typevehicule

* @param $libelleCatFraisKm
* @param $montantCatFraisKm
*/
    public function ajoutCatFraisKm($libelleCatFraisKm){
        $libelleCatFraisKm = addslashes(htmlspecialchars($libelleCatFraisKm));
        $req = "insert into typevehicule(libelleTypeVehicule) values('$libelleCatFraisKm')";
    PdoGsb::$monPdo->exec($req);
}
/**
* Met à jour la table typevehicule

* @param $libCatFraisKm tableau associatif de clé idTypeVehicule et de valeur le libellé saisi dans le formulaire de modification
* @param $montantCatFraisKm tableau associatif de clé idTypeVehicule et de valeur le montant saisi dans le formulaire de modification
*/
    public function majCatFraisKm($idCatFraisKm,$libCatFraisKm){
        $libCatFraisKm = addslashes(htmlspecialchars($libCatFraisKm));
        $req = "update typevehicule set typevehicule.libelleTypeVehicule = '$libCatFraisKm'where typevehicule.idTypeVehicule = '$idCatFraisKm'";
    PdoGsb::$monPdo->exec($req);
}
/**
* Suppression d'un tuple dans la table typevehicule

* @param $idCatFraisKm
*/
    public function supprimerCatFraisKm($idCatFraisKm){
        $req = "delete from typevehicule where typevehicule.idTypeVehicule = '$idCatFraisKm'";
    PdoGsb::$monPdo->exec($req);
}
/**
* Retourne les catégories "Frais forfaitisé" existantes dans la BDD

* @return un tableau associatif de clé idFraisForfaitise et de valeurs libelleFF et montantFF
*/
    public function getLesCatFraisForfait($idCatFraisForfait=NULL){
        $req = "select fraisforfaitise.idFraisForfaitise as idFraisForfaitise, fraisforfaitise.libelleFF as libelleFF, fraisforfaitise.montantFF as montantFF from fraisforfaitise";
    if(!is_null($idCatFraisForfait)){
        $req .= " where fraisforfaitise.idFraisForfaitise = '$idCatFraisForfait'";
}
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
        return $laLigne;
}
/**
* Création d'un nouveau tuple dans la table fraisforfaitise

* @param $idCatFrais
* @param $libelleCatFrais
* @param $montantCatFrais
*/
    public function ajoutCatFraisForfait($idCatFrais,$libelleCatFrais, $montantCatFrais){
        $libelleCatFrais = addslashes(htmlspecialchars($libelleCatFrais));
        $req = "insert into fraisforfaitise(idFraisForfaitise,libelleFF,montantFF) values('$idCatFrais','$libelleCatFrais','$montantCatFrais')";
    PdoGsb::$monPdo->exec($req);
}
/**
* Met à jour la table fraisforfaitise

* @param $libCatFraisForfait
* @param $montantCatFraisForfait
*/
    public function majCatFraisForfait($idCatFraisForfait,$libCatFraisForfait,$montantCatFraisForfait){
        $libCatFraisForfait = addslashes(htmlspecialchars($libCatFraisForfait));
        $req = "update fraisforfaitise set fraisforfaitise.libelleFF = '$libCatFraisForfait', fraisforfaitise.montantFF = '$montantCatFraisForfait'
        where fraisforfaitise.idFraisForfaitise = '$idCatFraisForfait'";
        PdoGsb::$monPdo->exec($req);
}
/**
* Suppression d'un tuple dans la table fraisforfaitise

* @param $idCatFrais
*/
    public function supprimerCatFraisForfait($idCatFrais){
        $req = "delete from fraisforfaitise where fraisforfaitise.idFraisForfaitise = '$idCatFrais'";
    PdoGsb::$monPdo->exec($req);
}
/**
* Retourne les comptes utilisateur GSB existants dans la BDD

* @param $idUtilisateur (facultatif)
* @return un tableau avec les informations concernant chaque employé
*/
    public function getLesCptUtilisateur($idUtilisateur=NULL, $typeUtilisateur=NULL){
        $id = $idUtilisateur;
        $type = $typeUtilisateur;
        $req = "select * from employe
        inner join typeemploye on typeemploye.idTypeEmploye = employe.idTypeEmploye
        inner join localisation on localisation.idLocalisation = employe.idLocalisation
        left join typevehicule on typevehicule.idTypeVehicule = employe.idTypeVehicule";
    if (!is_null($id)){
        $req .= " where employe.idEmploye = '$id'";
}
    if (!is_null($type)){
        $req .= " where employe.idTypeEmploye = '$type'";
}
        $req .= " order by employe.nom asc";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
        return $laLigne;
}
/**
* Création d'un nouveau tuple dans la table typevehicule

* @param $nom
* @param $prenom
* @param $adresse
* @param $login
* @param $mdp
* @param $dateEmbauche
* @param $typeEmploye
* @param $fraisKm
* @param $localisation
* @param $etatCpt
* @param $dateCreationCpt
*/
    public function ajoutCptUtilisateur($nom, $prenom, $adresse, $login, $mdp, $dateEmbauche, $typeEmploye, $fraisKm, $localisation, $etatCpt, $dateCreationCpt){
        $nom = addslashes(htmlspecialchars($nom));
        $prenom = addslashes(htmlspecialchars($prenom));
        $adresse = addslashes(htmlspecialchars($adresse));
        if ($fraisKm == 0){
        $fraisKm = "null";
}
        $dateEmbauche = dateFrancaisVersAnglais($dateEmbauche);
        $req = "insert into employe(nom,prenom,adresse,login,mdp,dateEmbauche,idTypeEmploye,idTypeVehicule,idLocalisation,cptActif,dateModifFicheE)
        values('$nom','$prenom','$adresse','$login','$mdp','$dateEmbauche','$typeEmploye',$fraisKm,'$localisation','$etatCpt','$dateCreationCpt')";
        PdoGsb::$monPdo->exec($req);
}
/**
* Met à jour le compte utilisateur dans employe portant l'idEmploye correspondant

* @param $idEmploye
* @param $nom
* @param $prenom
* @param $adresse
* @param $login
* @param $mdp
* @param $dateEmbauche
* @param $typeEmploye
* @param $fraisKm
* @param $localisation
* @param $etatCpt
* @param $dateMajCpt
*/
    public function majCptUtilisateur($idEmploye, $nom, $prenom, $adresse, $login, $mdp, $dateEmbauche, $typeEmploye, $fraisKm, $localisation, $etatCpt, $dateMajCpt){
        $nom = addslashes(htmlspecialchars($nom));
        $prenom = addslashes(htmlspecialchars($prenom));
        $adresse = addslashes(htmlspecialchars($adresse));
        $dateEmbauche = dateFrancaisVersAnglais($dateEmbauche);
        $req = "update employe set
        employe.nom = '$nom',
        employe.prenom = '$prenom',
        employe.adresse = '$adresse',
        employe.login = '$login',";
    if ($mdp != ""){
        $req = $req . "employe.mdp = '$mdp',";
}
        $req = $req . "employe.dateEmbauche = '$dateEmbauche',
        employe.idTypeEmploye = '$typeEmploye',
        employe.idTypeVehicule = $fraisKm,
        employe.idLocalisation = '$localisation',
        employe.cptActif = '$etatCpt',
        employe.dateModifFicheE = '$dateMajCpt'
        where employe.idEmploye = '$idEmploye'";
        PdoGsb::$monPdo->exec($req);
}
/**
* Suppression d'un tuple dans la table employe

* @param $idCptUtilisateur
*/
    public function supprimerCptUtilisateur($idCptUtilisateur){
        $req = "delete from employe where employe.idEmploye = '$idCptUtilisateur'";
        PdoGsb::$monPdo->exec($req);
}
/**
* Retourne les "données fixes" communes aux différents formulaires

* @return un tableau associatif
*/
    public function getLesDonnéesFixesForm($tableSelect,$champSelect){
        $table = $tableSelect;
        $nomChamp = $champSelect;
        $req = "select * from $table order by $nomChamp";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
        return $laLigne;
}
/** Vérifie si une catégorie a des dépendances dans la BDD, soit dont l'ID est utilisé dans une autre table en tant que clé étrangère (vérif. avant suppression d'une catégorie)

* @param $idFHF
* @return le nombre de justificatif
*/
    public function verifierDependanceCategorie($idCatAVerifier,$nomTableAVerifier){
        $aDesDependances = false;
    if ($nomTableAVerifier == 'typeEmploye'){
        $req = "select count(*) as nbrDependance from employe where employe.idTypeEmploye = '$idCatAVerifier'";
}
    if ($nomTableAVerifier == 'typeVehicule'){
        $req = "select count(*) as nbrDependance from employe where employe.idTypeVehicule = '$idCatAVerifier'";
}
    if ($nomTableAVerifier == 'employe'){
        $req = "select count(*) as nbrDependance from fichefrais where fichefrais.emp_idEmploye = '$idCatAVerifier' or fichefrais.idEmploye = '$idCatAVerifier'";
}
    if ($nomTableAVerifier == 'fraisForfaitise'){
        $req = "select count(*) as nbrDependance from ligneFraisForfaitise where ligneFraisForfaitise.idFraisForfaitise = '$idCatAVerifier'";
}
    $res = PdoGsb::$monPdo->query($req);
        $nbDependance = $res->fetch(PDO::FETCH_ASSOC);
        $nbDependance = (int) $nbDependance['nbrDependance'];
    if ($nbDependance > 0){
        $aDesDependances = true;
}
    return $aDesDependances;
}
}
?>
