<?php
/**
*	Ccc_Admin_Model_Timezone
**/
class Admin_Model_Timezone extends Core_Model_Timezone
//class Admin_Model_Timezone extends Core_Model_Abstract
{
	/**
	*	Get Timezone Function. This function is used to get Timezone.
	*  	@return timezone.	
	**/
	public function getTimezone()
    {                              
        return Ccc::getModel("config/system_config")->getSystemConfig("general/locale/timezone");
    }
}