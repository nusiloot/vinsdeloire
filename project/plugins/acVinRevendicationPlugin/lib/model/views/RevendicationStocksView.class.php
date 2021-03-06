<?php
class RevendicationStocksView extends acCouchdbView
{
    const KEY_CAMPAGNE = 0;
    const KEY_ETABLISSEMENT_IDENTIFIANT = 1;
    const KEY_PRODUIT_HASH = 2;
    const KEY_ODG = 3;
    const KEY_ID = 4;

    const VALUE_VOLUME = 0;
    const VALUE_DECLARANT_NOM = 1;
    const VALUE_PRODUIT_LIBELLE = 2;

    public static function getInstance() {

        return acCouchdbManager::getView('revendication', 'stocks', 'Revendication');
    }

    public function findByCampagneAndEtablissement($campagne, $etablissement_identifiant) {    

        return $this->builds(
                            $this->client->startkey(array($campagne, $etablissement_identifiant))
                            ->endkey(array($campagne, $etablissement_identifiant, array()))
                            ->getView($this->design, $this->view)->rows
                            );
    }

    public function builds($rows) {
        $revs = array();

        foreach($rows as $row) {
            $key = $row->key[self::KEY_ETABLISSEMENT_IDENTIFIANT] . '_' . $row->key[self::KEY_PRODUIT_HASH];
            $row = $this->build($row);
            if ($row) {
                $revs[$key] = $row;
            }
        }

        return $revs;
    }

    public function build($row) {
        $rev = new stdClass();
        $rev->odg = $row->key[self::KEY_ODG];
        $rev->produit_hash = $row->key[self::KEY_PRODUIT_HASH];
        if (RevendicationStocksODGView::shouldBeFilteredIGPAOP($rev->odg, $rev->produit_hash)) {
            return null;
        }
        $rev->campagne = $row->key[self::KEY_CAMPAGNE];
        $rev->etablissement_identifiant = $row->key[self::KEY_ETABLISSEMENT_IDENTIFIANT];
        $rev->declarant = new stdClass();
        $rev->declarant->nom = $row->value[self::VALUE_DECLARANT_NOM];
        $rev->campagne = $row->key[self::KEY_CAMPAGNE];
        $rev->produit_libelle = $row->value[self::VALUE_PRODUIT_LIBELLE];
        $rev->volume = $row->value[self::VALUE_VOLUME];
	if (!isset($row->_id))
		$row->_id = null;
        $rev->_id = $row->_id;

        return $rev;
    }
}  
