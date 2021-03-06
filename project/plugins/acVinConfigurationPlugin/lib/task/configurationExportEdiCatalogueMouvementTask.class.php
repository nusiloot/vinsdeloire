<?php

class configurationExportEdiCatalogueMouvementTask extends sfBaseTask
{

  public static $escaped_mvts_keys = array();

  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'configuration';
    $this->name             = 'export-edi-catalogue-mouvement';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [configurationExportEdiCatalogueMouvement|INFO] task does things.
Call it with:

  [php symfony configurationExportEdiCatalogueMouvementTask|INFO] exporte les mouvements de la configuration courrante
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $configuration = ConfigurationClient::getCurrent();
    if(!$configuration){
      throw new sfException("La configuration courante n'existe pas");
    }
    echo sprintf("TYPE_DRM,CATEGORIE MOUVEMENT,TYPE MOUVEMENT,LIBELLE MOUVEMENT,DESCRIPTION MOUVEMENT,CARACTERISTIQUES,DOUANE\n");

    $configurationDeclaration = $configuration->getDeclaration();
    $typesMvt = DRMClient::$types_libelles;

    $libelles = $configuration->libelle_detail_ligne;

    foreach ($typesMvt as $typesMvtKey => $typesMvtValue) {
      foreach ($configurationDeclaration->$typesMvtKey as $categorieKey => $categorieValue) {
        $categorieArray = array();
        foreach ($categorieValue as $mvtKey => $mvtValue) {
            if (!$mvtValue->readable) {
                continue;
            }
            if(!in_array($mvtKey,self::$escaped_mvts_keys)){
                if (strpos($categorieKey, 'stocks') !== false && $mvtKey != 'revendique') {
                    continue;
                }
                $properties = array();
                if($mvtValue->isVrac()) {
                    $properties[] = "CONTRAT";
                }
                if(preg_match("/export/", $mvtValue->getKey())) {
                    $properties[] = "PAYS";
                }
                if($mvtValue->needDouaneDateReplacement()) {
                    $properties[] = "DATE_REPLACEMENT";
                }
                if($mvtValue->needDouaneObservation()) {
                    $properties[] = "OBSERVATIONS";
                }
                $libelleMvt = str_replace(',', '.', strtolower($libelles->$typesMvtKey->$categorieKey->$mvtKey->libelle));
                $description = str_replace(',', '.', strtolower($libelles->$typesMvtKey->$categorieKey->$mvtKey->description));
                $categorieArray[$libelleMvt] = sprintf("%s,%s,%s,%s,%s,%s,%s\n",strtolower(KeyInflector::slugify($typesMvtValue)),$categorieKey,$mvtKey,$libelleMvt,$description, implode(";", $properties), $mvtValue->douane_cat);
            }
        }
        ksort($categorieArray);
        foreach ($categorieArray as $row) {
          echo $row;
        }
      }
    }

  }
}
