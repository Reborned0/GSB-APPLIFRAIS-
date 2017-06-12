<div class="contenu">
    <div class="contenu_erreur">
        <div class ="erreur">
        <?php
        if (isset($messageErreur)){
            echo "<ul><li>" . $messageErreur . "</li></ul>";
        }
        ?>
            <p>Les erreurs suivantes ont été constatées :</p>
            
            <ul>
                <?php
                if (isset($_REQUEST['erreurs'])){
                    foreach($_REQUEST['erreurs'] as $erreur){
                        echo "<li>$erreur</li>";
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>
