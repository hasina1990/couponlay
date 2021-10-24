<?php
/**
*	Ccc_Core_Model_Locale
**/
class Core_Model_Locale extends Zend_Locale
{
    /**
    *   Get Country List Function. Used to get List of all countries.
    *   @return countries.
    **/   
	public function getCountryList()
	{
	    return $this->getTranslationList('territory', null, 2);
        
	}
       
}