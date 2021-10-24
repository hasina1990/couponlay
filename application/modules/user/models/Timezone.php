<?php
/**
*	Ccc_Front_Model_Timezone
**/
class User_Model_Timezone extends Core_Model_Timezone
{
	/**
    *	$_timezone protected Variable. Used to Store the Timezone.
	*	@var mixed
    **/
	protected $_timezone = null;

	/**
	*	Get Timezone Function. This function is used to get Timezone.
	*  	@return timezone.	
	**/
	public function getTimezone()
	{
		if($this->_timezone)
		{
			return $this->_timezone;
		}
		
		$profile = Ccc::registry('current_profile');
		
		if($profile instanceof Ccc_Front_Model_Profile_Row) 
		{
			if($address = $profile->getAddress(Ccc_Core_Model_Profile_Address::ADDRESS_TYPE_CURRENT))
			{
				if($address->time_zone)
				{
					$this->_timezone = $address->time_zone;
					return $this->_timezone;
				}	
			}    
		}
		$this->_timezone = Ccc::getModel("admin/timezone")->getTimezone();
		return $this->_timezone;
	}
}