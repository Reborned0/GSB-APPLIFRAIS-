<?php
$uc = $_GET['uc'];
$ucRenvoi = "controlerFrais";
$messageTitre = "Fiches de frais à valider";
if ($uc == "suivreFrais"){
    $ucRenvoi = "suivreFrais";
    $messageTitre = "Suivi des fiches de frais";
}
?>

<div class="contenu contenu_premier">
    <h2><?php echo $messageTitre; ?></h2>
    <div class="contenu_interne">
        <form method="POST" action="index.php?uc=<?php echo $ucRenvoi; ?>&action=selectionnerMois">
            <fieldset>
                <legend>Sélectionnez un visiteur : </legend>
                <div class="corpsForm">
                    <label for="1stVisiteur" accesskey="n">Visiteur : </label>
                    <select id="1stVisiteur" name="1stVisiteur">
                    <?php
                    foreach ($lstVisiteur as $unVisiteur){
                        $idVisiteur = $unVisiteur['idEmploye'];
                        $nomVisiteur = $unVisiteur['nom'];
                        $prenomVisiteur = $unVisiteur['prenom'];
                        if ($idVisiteur == $_POST['1stVisiteur'] || $idVisiteur == $leVisiteur){ ?>
                            <option value="<?php echo $idVisiteur  ?>" selected><?php echo $nomVisiteur ." ".$prenomVisiteur; ?> </option>
                        <?php
                        }
                        else { ?>
                            <option value="<?php echo $idVisiteur ?>"><?php echo $nomVisiteur . " " . $prenomVisiteur; ?> </option>
                        <?php
                        }
                    }?>
                    </select>
                </div>
                <div class="piedForm">
                    <input id="ok" type="submit" value="Valider" size="20" />
                    <input id="annuler" type="reset" value="Corriger" size="20" />
                </div>
            </fieldset>
        </form>
    </div>
</div>
