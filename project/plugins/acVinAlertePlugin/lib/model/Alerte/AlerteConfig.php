<?php
class AlerteConfig  {

    
    protected $config = null;
    
    
    public function __construct($typeAlerte) {
        $configs = sfConfig::get('app_alertes_generations');
        if (!array_key_exists($typeAlerte, $configs))
            throw new sfException(sprintf('Config %s not found in app.yml', $typeAlerte));
        $this->config = $configs[$typeAlerte];
    }
     public function getOption($field) {
        if (!isset($this->config[$field]))
            return null;
        return $this->config[$field];
    }

    public function getOptionDate($field) {
        $dates = array();
        preg_match('/^([0-9]+)\/([0-9]+)/', $this->getOption($field), $dates);
        return sprintf('%04d-%02d-%02d', date('Y'), $dates[2], $dates[1]);
    }

    public function getOptionDelaiDate($field, $date = null) {
        if (!$date)
            $date = date('Y-m-d');
        $delai = $this->getOption($field);
        if (!$delai) {
            return null;
        }
        return Date::addDelaiToDate($delai, $date);
    }
}