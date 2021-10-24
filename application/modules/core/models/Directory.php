<?php
/**
*	Ccc_Core_Model_Directory
**/
class Core_Model_Directory extends Core_Model_Abstract
{   
    /**
    *   Get Country Options Function. Used to get country names as options.
    *   @return countries.
    **/
	public function getCountryOptions()
	{
		$countries = Ccc::getModel('core/locale')->getTranslationList('territory', null, 2);
        
        return $this->_filterCountryList($countries); 
        
        asort($countries);
        return $countries;
	}
    
    public function _filterCountryList($countryList)
    {        
        $timeZoneList = Ccc::getModel("core/timezone")->_getTerritoryToTimezoneList();
        
        $timeZones = array_flip($timeZoneList);
        
        $validCountry = array_intersect_key($timeZones,$countryList);
                
        foreach($countryList as $_code=>$_name)
        {
            if(!isset($validCountry[$_code]))
            {
                unset($countryList[$_code]);
            }        
        }
        
        unset($countryList['001']);
        
        return $countryList;
        
       
    }        	
}