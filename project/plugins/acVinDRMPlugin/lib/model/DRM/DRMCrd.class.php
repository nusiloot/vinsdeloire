<?php
/**
 * Model for DRMCrd
 *
 */

class DRMCrd extends BaseDRMCrd {
    
    public function getLibelle(){
        return DRMClient::$drm_crds_couleurs[$this->couleur].' - '.$this->centilitrage;
    }
    
    public function udpateStockFinDeMois() {
        $this->stock_fin = $this->stock_debut + $this->entrees - $this->sorties - $this->pertes;
    }
}