<?php
/**
*	Ccc_Admin_Model_Date
**/
class User_Model_Date extends Core_Model_Date
{
	/**
    *	Get Formatted Date View Function. This function is used to get formatted date format for system with timezone
    *   @param mixed $date.
    *   @return date|null.
    **/
    public function getFormattedDateView($date)
    {
        if(!self::isDate($date))
        {
            return null;
        }
        
        $date = new Zend_Date(strtotime($date));
        $date->addTimestamp($this->_getTimezoneModel()->getTimezoneOffset());
        return (string)$date;
    }
	
	/**
    *	Get Formatted Time View Function. This function is used to get formatted Time format for system with timezone
    *   @param mixed $time.
    *   @return time|null.
    **/
	public function getFormattedTimeView($time)
    {
        if(!self::isTime($time))
        {
            return null;
        }
        
        $date = new Zend_Date();
        $date->set($time,Zend_Date::TIMES);
		$date->addTimestamp($this->_getTimezoneModel()->getTimezoneOffset());		
        return $date->get(Zend_Date::TIME_SHORT);  
    }
	
	/**
    *  	_getTimezoneModel Function. Used to get Timezone Model.
    *   @return object.
    */
	protected function _getTimezoneModel()
	{
		return Ccc::getModel('admin/timezone');
	}
}