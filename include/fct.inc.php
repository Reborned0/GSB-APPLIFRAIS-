<?php
/** 
 * Fonctions pour l'application GSB
 
 * @package default
 * @author Souvannarith CHEA
 * @version    2.0
 */
 /**
 * Teste si un quelconque visiteur est connecté
 * @return vrai ou faux 
 */
function estConnecte(){
  return isset($_SESSION['idVisiteur']);
}
/**
 * Enregistre dans une variable session les infos d'un visiteur
 
 * @param $id 
 * @param $nom
 * @param $prenom
 */
function connecter($id,$nom,$prenom,$type){
    $_SESSION['idVisiteur']= $id; 
    $_SESSION['nom']= $nom;
    $_SESSION['prenom']= $prenom;
    $_SESSION['type']= $type;
}
/**
 * Détruit la session active
 */
function deconnecter(){
    session_destroy();
}
/**
 * Vérifie si la date du jour correspond au 10 du mois (= 1er jour de campagne comptable)

 * @param $jour au format jj
 * @return vrai ou faux
 */
function verifEstDebutCampagneDuMois($jour){
    $estDebutCampagneDuMois = true;
    if ($jour >= 1 and $jour <= 9){
        $estDebutCampagneDuMois = false;
    }
    return $estDebutCampagneDuMois;
}
/**
 * Vérifie si la date du jour correspond au 20 du mois (= dernier jour de campagne comptable)

 * @param $jour au format jj
 * @return vrai ou faux
 */
function verifDernierJourCampagneDuMois($jour){
    $estDernierJourCampagneDuMois = false;
    if ($jour == '20'){
        $estDernierJourCampagneDuMois = true;
    }
    return $estDernierJourCampagneDuMois;
}
/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
 
 * @param $maDate au format  jj/mm/aaaa
 * @return la date au format anglais aaaa-mm-jj
*/
function dateFrancaisVersAnglais($maDate){
    list($jour,$mois,$annee) = explode('/',$maDate);
    return date('Y-m-d',mktime(0,0,0,$mois,$jour,$annee));
}
/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa

 * @param $maDate au format  aaaa-mm-jj
 * @return la date au format format français jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate){
    list($annee,$mois,$jour)=explode('-',$maDate);
   $date="$jour"."/".$mois."/".$annee;
   return $date;
}
/**
 * Transforme un id date au format format aaaajj vers un format date raccourci mm-aaaa à afficher

 * @param $idDate au format  aaaamm
 * @return la date au format format mm-aaaa
 */
function formatageIdDate($idDate){
    $dateAFormater = $idDate;
    $mois = substr($dateAFormater,-2);
    $annee = substr($dateAFormater,0,4);
    $dateFormatCourt = $mois . "-" . $annee;
    return $dateFormatCourt;
}
/**
 * Transforme un nombre décimal avec une virgule comme séparateur (format français) en nombre décimal avec un point comme séparateur (format anglais)

 * @param $nbr au format 1,5
 * @return le nombre au format format 1.5
 */
function formatageNbDecimal($nbr){
    $nbr = str_replace(",",".",$nbr);
    $nbr = floatval($nbr);
    return $nbr;
}
/**
 * retourne le mois au format aaaamm selon le jour dans le mois
 
 * @param $date au format  jj/mm/aaaa
 * @return le mois au format aaaamm
*/
function getMois($date){
    list($jour,$mois,$annee) = explode('/',$date);
    if(strlen($mois) == 1){
        $mois = "0".$mois;
    }
    return $annee.$mois;
}
/* gestion des erreurs*/
/**
 * Indique si une valeur est un entier positif ou nul
 
 * @param $valeur
 * @return vrai ou faux
*/
function estEntierPositif($valeur) {
    return preg_match("/[^0-9]/", $valeur) == 0;
}
/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 
 * @param $tabEntiers : le tableau
 * @return vrai ou faux
*/
function estTableauEntiers($tabEntiers) {
    $ok = true;
    foreach($tabEntiers as $unEntier){
        if(!estEntierPositif($unEntier)){
            $ok = false;
        }
    }
    return $ok;
}
/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 
 * @param $dateTestee 
 * @return vrai ou faux
*/
function estDateDepassee($dateTestee){
    $dateActuelle = date("d/m/Y");
    list($jour,$mois,$annee) = explode('/',$dateActuelle);
    $annee--;
    $AnPasse = $annee.$mois.$jour;
    list($jourTeste,$moisTeste,$anneeTeste) = explode('/',$dateTestee);
    return ($anneeTeste.$moisTeste.$jourTeste < $AnPasse); 
}
/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa 
 
 * @param $date 
 * @return vrai ou faux
*/
function estDateValide($date){
    $tabDate = explode('/',$date);
    $dateOK = true;
    if (count($tabDate) != 3) {
        $dateOK = false;
    }
    else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        }
        else {
            if (strlen($tabDate[2]) != 4){
                $dateOK = false;
            }
            else{
                if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                    $dateOK = false;
                }
            }
        }
    }
    return $dateOK;
}
/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques 
 
 * @param $lesFrais 
 * @return vrai ou faux
*/
function lesQteFraisValides($lesFrais){
    return estTableauEntiers($lesFrais);
}
/**
 * Vérifie si un login existe déjà dans la BDD ou non

 * @param $login
 * @param $liste_loginBDD
 * @return vrai ou faux
 */
function verifLogin($login,$liste_loginBDD){
    $loginExiste = false;
    if(in_array($login,$liste_loginBDD)){
        $loginExiste = true;
    }
    return $loginExiste;
}
/**
 * Vérifie la validité des arguments : id et libellé du type employé

 * Des message d'erreurs sont ajoutés au tableau des erreurs

 * @param $id
 * @param $libelle
 */
function valideInfosCatEmploye($id,$libelle){
    if($id == ""){
        ajouterErreur("Le champ ID ne peut pas être vide");
    }
    else{
        if(strlen($id) != 3){
            ajouterErreur("Le format de l'ID est incorrect, doit comporter 3 caractères");
        }
        else{
            if (!preg_match('#^[[:alnum:]]*$#', $id)) {
                ajouterErreur("Le champ ID ne peut comporter que des lettres et/ou des chiffres");
            }
        }
    }
    if($libelle == ""){
        ajouterErreur("Le champ libellé ne peut pas être vide");
    }
}
/**
 * Vérifie la validité de l'argument libellé du type du véhicule

 * Des message d'erreurs sont ajoutés au tableau des erreurs

 * @param $libelle
 */
function valideInfosCatFraisKm($libelle){
    if($libelle == ""){
        ajouterErreur("Le champ libellé ne peut pas être vide");
    }
}
/**
 * Vérifie la validité des arguments : id, libellé et montant du type frais forfaitisé

 * Des message d'erreurs sont ajoutés au tableau des erreurs

 * @param $id
 * @param $libelle
 * @param $montant
 */
function valideInfosCatFraisForfait($id,$libelle,$montant){
    if($id == ""){
        ajouterErreur("Le champ ID ne peut pas être vide");
    }
    else{
        if(strlen($id) != 3){
            ajouterErreur("Le format de l'ID est incorrect, doit comporter 3 caractères");
        }
        else{
            if (!preg_match('#^[[:alnum:]]*$#', $id)) {
                ajouterErreur("Le champ ID ne peut comporter que des lettres et/ou des chiffres");
            }
        }
    }
    if($libelle == ""){
        ajouterErreur("Le champ libellé ne peut pas être vide");
    }
    if(is_string($montant) && $montant == ""){
        ajouterErreur("Le champ montant ne peut pas être vide");
    }
    elseif(!is_numeric($montant) || $montant == 0){
        ajouterErreur("Le champ montant doit être numérique et positif");
    }
}
/**
 * Vérifie la validité des arguments concernant le compte utilisateur

 * Des message d'erreurs sont ajoutés au tableau des erreurs
 */
function valideInfosCptUtilisateur($nom,$prenom,$adresse,$localisation,$login,$liste_loginBDD,$mdp,$dateEmbauche,$typeEmploye,$verif_loginEtMdp=true){
    if($nom == ""){
        ajouterErreur("Le champ nom ne peut pas être vide");
    }
    if($prenom == ""){
        ajouterErreur("Le champ prénom ne peut pas être vide");
    }
    if($adresse == ""){
        ajouterErreur("Le champ adresse ne peut pas être vide");
    }
    if($localisation == 0){
        ajouterErreur("La ville doit être renseignée");
    }
    if($verif_loginEtMdp == true){
        if($login == ""){
            ajouterErreur("Le champ login ne peut pas être vide");
        }
        else{
            if (!preg_match('#^[[:alnum:]]*$#', $login)) {
                ajouterErreur("Le login ne peut comporter que des lettres et/ou des chiffres");
            }
            else{
                $loginExiste = verifLogin($login,$liste_loginBDD);
                if($loginExiste == true){
                    ajouterErreur("Login déjà pris, définir un autre login");
                }
            }
        }
    }
    if($verif_loginEtMdp == true){
        if($mdp == ""){
            ajouterErreur("Le champ mot de passe ne peut pas être vide");
        }
        else{
            if (!preg_match('#^[[:alnum:]&@$]*$#', $mdp)) {
                ajouterErreur("Le mot de passe ne peut comporter que des lettres et/ou des chiffres et/ou les caractères spéciaux suivants : & @ $");
            }
        }
    }
    if($dateEmbauche == ""){
        ajouterErreur("La date d'embauche doit être renseignée");
    }
    else{
        if(!estDatevalide($dateEmbauche)){
            ajouterErreur("Date invalide");
        }
    }
    if($typeEmploye == ""){
        ajouterErreur("Le type d'employé doit être renseigné");
    }
}
/**
 * Vérifie la validité des arguments : date, libellé et montant du frais

 * Des message d'erreurs sont ajoutés au tableau des erreurs
 
 * @param $dateFrais 
 * @param $libelle 
 * @param $montant
 */
function valideInfosFrais($dateFrais,$libelle,$montant){
    if($dateFrais == ""){
        ajouterErreur("Le champ date ne peut pas être vide");
    }
    else{
        if(!estDatevalide($dateFrais)){
            ajouterErreur("Date invalide");
        }	
        else{
            if(estDateDepassee($dateFrais)){
                ajouterErreur("Date d'enregistrement du frais dépassé de plus de 1 an");
            }			
        }
    }
    if($libelle == ""){
        ajouterErreur("Le champ description ne peut pas être vide");
    }
    if(is_string($montant) && $montant == ""){
        ajouterErreur("Le champ montant ne peut pas être vide");
    }
    elseif(!is_numeric($montant) || $montant == 0){
        ajouterErreur("Le champ montant doit être numérique et positif");
    }
}
/** Vérifie que la taille du fichier ($tailleFichier) n'excède pas la taille maximale autorisée de téléchargement ($tailleMaxFichier)

 * @param $tailleFichier
 * @param $tailleMaxFichier
 */
function valideJustificatif($nomFichier,$tailleFichier,$tailleMaxFichier,$codeErreur){
    $extensionFichier = substr($nomFichier,(stripos($nomFichier,".") + 1));
    $extensionAutorisee = array('jpg', 'jpeg', 'png', 'pdf');
    if (in_array($extensionFichier,$extensionAutorisee) == FALSE){
        ajouterErreur("Le type du fichier n'est pas accepté (télécharger un fichier au format valide : pdf, png, jpeg ou jpg)");
    }
    if($codeErreur == 1 || $codeErreur ==2 || ($tailleFichier > $tailleMaxFichier)){
        ajouterErreur("La taille du fichier à télécharger ne peut excéder 2Mo");
    }
    else{
        if($codeErreur > 0){
            ajouterErreur("Une erreur est survenue lors du transfert de fichier");
        }
    }
}
/** Renomme le nom du fichier au format voulu et indique le dossier de destination du fichier (dossier files à la racine)

 * @param $nom
 * @param $mois
 * @param $idVisiteur
 * @param $idFHF
 * @return le nom du fichier formaté : emplacement/nomDuFichier.extension => files/exemple.pdf
 */
function renommageNomFichier($nom,$mois,$idVisiteur,$idFHF){
    $idVisiteur = $idVisiteur;
    if (is_array($idFHF)){
        $idFHF = $idFHF[0]['idLigneFHF'];
    }

    $dossierDestination = "./files/";
    $nomFichierTemp = $mois . "_idFHF" . $idFHF . "_idVisiteur" . $idVisiteur . "_" . time();
    $extension = substr($nom,stripos($nom,"."));
    $nomFichier = $dossierDestination . $nomFichierTemp . $extension;
    return $nomFichier;
}
/** Déplace le fichier téléchargé du fichier temporaire serveur (tmp) à l'emplacement de destination (files)

 * @param $nom
 * @param $emplacementFinal
 * @return bool : indique si le transfert du fichier a bien été effectué ou non
 */
function enregistrementFichier($nom,$emplacementFinal){
    $resEnregistrement = move_uploaded_file($emplacementFinal,$nom);
    return $resEnregistrement;
}
/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 
 * @param $msg : le libellé de l'erreur 
 */
function ajouterErreur($msg){
   if (!isset($_REQUEST['erreurs'])){
      $_REQUEST['erreurs'] = array();
   }
   $_REQUEST['erreurs'][] = $msg;
}
/**
 * Retoune le nombre de lignes du tableau des erreurs 
 
 * @return le nombre d'erreurs
 */
function nbErreurs(){
    if (!isset($_REQUEST['erreurs'])){
        return 0;
    }
    else{
       return count($_REQUEST['erreurs']);
    }
}
?>