<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">Contact</a> &gt; <strong>Création d'une société</strong></p>

    <!-- #contacts -->
    <section id="contacts">
        <div id="creation_societe">
            <h1>Création d'une nouvelle société</h1>
            <form action="<?php echo url_for('societe_modification', array('identifiant' => $societeForm->getObject()->identifiant)); ?>" method="post">
                <button id="btn_valider" type="submit" class="btn_majeur btnValidation">Valider</button>

                <div id="detail_societe" class="form_section">
                    <h2>Détail de la société</h2>  
                    <?php include_partial('societeModification', array('societeForm' => $societeForm)); ?>
                </div>
                <div id="coordonnees_societe" class="form_section">
                    <h2>Coordonnées de la société</h2>
                    <?php include_partial('compte/modification', array('compteForm' => $contactSocieteForm)); ?>                
                </div>
            </form>
        </div>
    </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>