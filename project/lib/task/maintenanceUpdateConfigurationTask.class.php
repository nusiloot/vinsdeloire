<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of maintenanceUpdateConfigurationTask
 *
 * @author mathurin
 */
class maintenanceUpdateConfigurationTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('dateconfiguration', sfCommandArgument::REQUIRED, "date de la configuration"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'update-configuration';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony maintenanceCompteStatut|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $dateconfiguration = $arguments['dateconfiguration'];
        $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION-' . $dateconfiguration, acCouchdbClient::HYDRATE_JSON);

        $import_dir = sfConfig::get('sf_data_dir') . '/import/configuration';


        if (!$configuration) {
            new sfException("La configuration n'existe pas");
        }

        unset($configuration->libelle_detail_ligne);
        unset($configuration->declaration->details);
        unset($configuration->declaration->detail);
        unset($configuration->mvts_favoris);
        ConfigurationClient::getInstance()->storeDoc($configuration);

        $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION-' . $dateconfiguration);

        foreach (file($import_dir . '/details_drm_' . $dateconfiguration . '.csv') as $line) {
            if (preg_match('/^#/', $line))
                continue;
            $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
            $detail = $configuration->get($datas[0])->getOrAdd($datas[1])->add($datas[2])->add($datas[3]);
            $detail->readable = (int) $datas[4];
            $detail->writable = (int) $datas[5];
            $detail->details = (int) $datas[6];
            $detail->mouvement_coefficient = (int) $datas[7];
            $detail->vrac = (int) $datas[8];
            $detail->facturable = (int) $datas[9];
            $detail->douane_type = $datas[10];
            $detail->douane_cat = $datas[11];
            $detail->taxable_douane = (int) $datas[12];
        }

        foreach (file($import_dir . '/libelle_detail_ligne_' . $dateconfiguration . '.csv') as $line) {
            $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
            $detail = $configuration->libelle_detail_ligne->getOrAdd($datas[0])->getOrAdd($datas[1]);
            $detail->libelle = $datas[2];
            $detail->libelle_long = $datas[3];
            $detail->description = $datas[4];
        }

        foreach (file($import_dir . '/mvts_favoris_' . $dateconfiguration . '.csv') as $mvtLine) {
            $mvt = explode(";", preg_replace('/"/', '', str_replace("\n", "", $mvtLine)));
            $configuration->getOrAdd('mvts_favoris')->add($mvt[0], $mvt[0]);
        }

        if (file_exists($import_dir . '/produits_' . $dateconfiguration . '.csv')) {
            $csv = new ProduitCsvFile($configuration, $import_dir . '/produits_' . $dateconfiguration . '.csv');
            $configuration = $csv->importProduits();
        }
        $configuration->save();
    }
}
