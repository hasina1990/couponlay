<?php
class Core_Model_Directory_Region extends Core_Model_Table_Abstract
{
    protected $_name = 'directory_region';
	protected $_primary = 'region_id';
	protected $_rowClass = 'Core_Model_Directory_Region_Row';
	protected $_rowsetClass = 'Core_Model_Directory_Region_Rowset';
    
    public function getTimzonesForCountry($countryId = null)
    {
        $state = $this->fetchAll("country_code = '{$countryId}'");
        if($state->count())
        {
            return $state;
        }
        return false;
    }
    
}