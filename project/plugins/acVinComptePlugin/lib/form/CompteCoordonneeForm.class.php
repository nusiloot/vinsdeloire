<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteSocieteModificationForm
 * @author mathurin
 */
class CompteCoordonneeForm extends acCouchdbObjectForm {

    private $compte;
    private $reduct_rights = false;

    public function __construct(Compte $compte, $reduct_rights = false, $options = array(), $CSRFSecret = null) {
        $this->compte = $compte;
        $this->reduct_rights = $reduct_rights;
        parent::__construct($compte, $options, $CSRFSecret);
        $this->defaults['pays'] = 'FR';
        if($compte->hasDroit(Roles::TELEDECLARATION_VRAC)){
            $this->defaults['droits'][] =  Roles::CONTRAT;
        }
        if($compte->hasDroit(Roles::TELEDECLARATION_DRM)){
            $this->defaults['droits'][] =  Roles::DRM;
        }
        if($compte->hasDroit(Roles::OBSERVATOIRE)){
            $this->defaults['droits'][] =  Roles::OBSERVATOIRE;
        }
        if($compte->hasDroit(Roles::TELEDECLARATION_DOUANE)){
            $this->defaults['droits'][] =  Roles::TELEDECLARATION_DOUANE;
        }

        if($compte->hasDroit(Roles::TELEDECLARATION_DRM_ACQUITTE)){
            $this->defaults['droits'][] =  Roles::TELEDECLARATION_DRM_ACQUITTE;
        }

        if($compte->hasDroit(Roles::TELEDECLARATION_FACTURE)){
            $this->defaults['droits'][] =  Roles::TELEDECLARATION_FACTURE;
        }

    }

    public function configure() {
        parent::configure();
        if (!$this->reduct_rights) {
            $this->setWidget('adresse', new sfWidgetFormInput());
            $this->setWidget('adresse_complementaire', new sfWidgetFormInput());
            $this->setWidget('code_postal', new sfWidgetFormInput());
            $this->setWidget('commune', new sfWidgetFormInput());
            $this->setWidget('cedex', new sfWidgetFormInput());
            $this->setWidget('pays', new sfWidgetFormChoice(array('choices' => $this->getCountryList()), array('class' => 'autocomplete')));
            $this->setWidget('droits', new sfWidgetFormChoice(array('choices' => $this->getDroits(), 'multiple' => true, 'expanded' => true)));
            $this->setWidget('region', new sfWidgetFormChoice(array('choices' => $this->getRegionsODG()), array('class' => 'autocomplete')));
        }

        $this->setWidget('email', new sfWidgetFormInput());
        $this->setWidget('telephone_perso', new sfWidgetFormInput());
        $this->setWidget('telephone_bureau', new sfWidgetFormInput());
        $this->setWidget('telephone_mobile', new sfWidgetFormInput());
        $this->setWidget('fax', new sfWidgetFormInput());
        $this->setWidget('site_internet', new sfWidgetFormInput());

        //   $this->setWidget('tags', new sfWidgetFormChoice(array('choices' => $this->getAllTags())));
        if (!$this->reduct_rights) {
            $this->widgetSchema->setLabel('adresse', 'N° et nom de rue *');
            $this->widgetSchema->setLabel('adresse_complementaire', 'Adresse complémentaire');
            $this->widgetSchema->setLabel('code_postal', 'CP *');
            $this->widgetSchema->setLabel('commune', 'Ville *');
            $this->widgetSchema->setLabel('cedex', 'Cedex');
            $this->widgetSchema->setLabel('pays', 'Pays *');
            $this->widgetSchema->setLabel('droits', 'Droits *');
            $this->widgetSchema->setLabel('region', 'Région Odg');
        }
        $this->widgetSchema->setLabel('email', 'E-mail');
        $this->widgetSchema->setLabel('telephone_perso', 'Telephone Perso.');
        $this->widgetSchema->setLabel('telephone_bureau', 'Telephone Bureau');
        $this->widgetSchema->setLabel('telephone_mobile', 'Mobile');
        $this->widgetSchema->setLabel('fax', 'Fax');
        $this->widgetSchema->setLabel('site_internet', 'Site Internet');

        //    $this->widgetSchema->setLabel('tags', 'Tags');

        if (!$this->reduct_rights) {
            $this->setValidator('adresse', new sfValidatorString(array('required' => true)));
            $this->setValidator('adresse_complementaire', new sfValidatorString(array('required' => false)));
            $this->setValidator('code_postal', new sfValidatorString(array('required' => true)));
            $this->setValidator('commune', new sfValidatorString(array('required' => true)));
            $this->setValidator('cedex', new sfValidatorString(array('required' => false)));
            $this->setValidator('pays', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCountryList()))));
            $this->setValidator('droits', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($this->getDroits()))));
            $this->setValidator('region', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getRegionsODG()))));
        }

        $this->setValidator('email', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_perso', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_bureau', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_mobile', new sfValidatorString(array('required' => false)));
        $this->setValidator('fax', new sfValidatorString(array('required' => false)));
        $this->setValidator('site_internet', new sfValidatorString(array('required' => false)));

        //  $this->setValidator('tags', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getAllTags()))));

        if($this->compte->isNew())
                $this->widgetSchema['statut']->setAttribute('disabled', 'disabled');
        $this->widgetSchema->setNameFormat('compte_modification[%s]');
    }

    public function getDroits() {

        return array(Roles::TELEDECLARATION_VRAC => "Contrat", Roles::TELEDECLARATION_DRM => "DRM", Roles::TELEDECLARATION_DRM_ACQUITTE => "DRM Acquittée", Roles::TELEDECLARATION_FACTURE =>  "Factures",Roles::TELEDECLARATION_FACTURE_EMAIL =>  "Factures par email",Roles::TELEDECLARATION_DREV =>  "Drev",Roles::TELEDECLARATION_DREV_ADMIN =>  "Administration Drev", Roles::OBSERVATOIRE =>  "Observatoire", Roles::TELEDECLARATION_DOUANE => 'Transmission douane');
    }

    public function getCountryList() {
        $destinationChoicesWidget = new sfWidgetFormI18nChoiceCountry(array('culture' => 'fr', 'add_empty' => true));
        $destinationChoices = $destinationChoicesWidget->getChoices();
        $destinationChoices['inconnu'] = 'Inconnu';
        return $destinationChoices;
    }

    public function getRegionsODG() {
        return array(""=> "", "ANJOU_SAUMUR" => "ANJOU_SAUMUR",
                              "TOURAINE" => "TOURAINE",
                              "CHINON" => "CHINON",
                              "NANTES" => "NANTES",
                              "COTEAUX_VENDOMOIS" => "COTEAUX_VENDOMOIS",
                              "IGP_VALDELOIRE" => "IGP_VALDELOIRE",
                              "SAINT_NICOLAS" => "SAINT_NICOLAS",
                              "HAUT_POITOU" => "HAUT_POITOU" ,
                              "VOUVRAY" => "VOUVRAY");
    }

    public function getAllTags() {
        return CompteClient::getInstance()->getAllTags();
    }

    protected function doSave($con = null) {
        if (null === $con) {
            $con = $this->getConnection();
        }

        $this->updateObject();
        if($this->compte->isNew()){
            $this->compte->statut = CompteClient::STATUT_ACTIF;
        }
        if($this->compte->isSocieteContact())
        {
            $this->compte->statut = $this->compte->getSociete()->statut;
            $this->compte->add('type_societe',$this->compte->getSociete()->type_societe);
        }
        $this->compte->updateDroits($this->getValue('droits'));
        if($this->compte->isEtablissementContact()){
            $this->compte->statut = $this->compte->getEtablissement()->statut;
        }
        if($region = $this->getValue('region')){
          $this->compte->add("region", $region);
        }else{
          $this->compte->remove("region");
        }

        $this->object->getCouchdbDocument()->save();
    }

}
