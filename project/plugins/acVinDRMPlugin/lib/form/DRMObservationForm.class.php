<?php

class DRMObservationForm extends BaseForm
{
	protected $_detail;

	public function __construct($detail, $options = array(), $CSRFSecret = null)
	{
		$this->_detail = $detail;
		parent::__construct($this->getDefaultValues(), $options, $CSRFSecret);
	}

    public function getDefaultValues() {
			$defaults = array();
			if($this->_detail->exist('observations')){
    		$defaults['observations'] = $this->_detail->observations;
			}
			if($this->_detail->exist('replacement_date')){
    		$defaults['replacement_date'] = $this->_detail->getReplacementDate();
			}
    	return  $defaults;
    }

	public function configure()
	{

		$dateRegexpOptions = array('required' => false,
				'pattern' => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/",
				'min_length' => 10,
				'max_length' => 10);
		$dateRegexpErrors = array('required' => 'Date obligatoire',
				'invalid' => 'Date invalide (le format doit être jj/mm/aaaa)',
				'min_length' => 'Date invalide (le format doit être jj/mm/aaaa)',
				'max_length' => 'Date invalide (le format doit être jj/mm/aaaa)');

        $w = array('observations' => new sfWidgetFormInput());
        $v = array('observations' => new sfValidatorString(array('required' => false)));
        $l = array('observations' => $this->_detail->getLibelle());

				if ($this->_detail->exist('replacement_date')) {
                $w['replacement_date'] = new sfWidgetFormInputText();
                $v['replacement_date'] = new sfValidatorRegex($dateRegexpOptions, $dateRegexpErrors);
                $l['replacement_date'] = "Date de la sortie du volume correspondant à ce réplacement";
        }
        $this->setWidgets($w);
        $this->setValidators($v);
        $this->widgetSchema->setLabels($l);
        $this->widgetSchema->setNameFormat('[%s]');
	}
}
