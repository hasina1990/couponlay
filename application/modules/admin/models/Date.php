<?php
/**
*	Ccc_Admin_Model_Date
**/
class Admin_Model_Date extends Core_Model_Date
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
    
    public function getDateDifference($startDate,$endDate)
    {
        if((strtotime($endDate) - strtotime($startDate))/ 86400)
        {
            return ((strtotime($endDate) - strtotime($startDate))/ 86400) + 1;
        }
        
        return 1;
    }
	public function getFormattedDate($date, $format = null, $formatRequire = null)
    {
        if(!self::isDate($date, $formatRequire))
        {
            return null;
        }
        
        if($format)
        {   
            return date($format, strtotime($date) + $this->_getTimezoneModel()->getTimezoneOffset());    
        }        
        $timezone_datetime = date($this->getDefualtFormat(), strtotime($date) + $this->_getTimezoneModel()->getTimezoneOffset());
        
        return $timezone_datetime;               
    }
    
    public function getDateRange($startDate,$daysCount)
    {
        $dates = array();
        $dates[0] = date('Y-m-d H:i:s', strtotime($startDate));
        for($i=1;$i<=$daysCount;$i++)
        {
            $dates[$i] = date('Y-m-d H:i:s', strtotime($startDate. "+$i days"));
        }
        return $dates;    
    }
	
	/**
    *  	_getTimezoneModel Function. Used to get Timezone Model.
    *   @return object.
    */
	protected function _getTimezoneModel()
	{
		return Ccc::getModel('admin/timezone');
	}
	public function getTimezoneFormattedDate($date,$timezoneOffset)
    {
        /*if(!self::isDate($date))
        {
            return null;
        }
        
        $date = new Zend_Date(strtotime($date));
        $date->addTimestamp($timezoneOffset);
        return (string)date($this->getDefualtFormat(), strtotime($date)) ;*/
        if(!self::isDate($date))
        {
          
            return null;
        }
        
        $timezone_datetime = date($this->getDefualtFormat(), strtotime($date) + $timezoneOffset);
        
        return $timezone_datetime;
    }
}