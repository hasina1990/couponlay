<?php
/**
*	Ccc_Core_Model_Date
**/
class Core_Model_Date extends Core_Model_Abstract
{
    const DATEPICKER_FORMAT_JS = 'mm/dd/yy';
    const DATEPICKER_FORMAT_PHP = 'm/d/Y';
    
    /**
    *	$_defualtFormat protected Variable. It contains defualt Format for date and time.
    *	@var mixed
    **/    
    protected $_defualtFormat = 'Y-m-d H:i:s';
    
    /**
    *	$_defualtFormatTime protected Variable. It contains defualt Format for time.
    *	@var mixed
    **/    
    protected $_defualtFormatTime = 'H:i:s';
    
    /**
    *	$_defualtFormatAPI protected Variable. It contains defualt Format for Api.
    *	@var mixed
    **/    
    protected $_defualtFormatAPI = 'Y-m-d\TH:i:s';
    
    public function getDateFormatForDatePickerJs()
    {
        return self::DATEPICKER_FORMAT_JS;
    }
    
    public function getDateFormatForDatePickerPhp()
    {
        return self::DATEPICKER_FORMAT_PHP;
    }
    
    /**
    *	Set Defualt Format Function. This function is used to set default date format.
    *	$format date format
    *   @return object
    **/
    public function setDefualtFormat($format)
    {	
        if(!$format)
        {
            $this->_defualtFormat = $format;
        }    
        return $this;
    }
    
    /**
    *	Set Defualt Format Time Function. This function is used to set default time format.
    *	@param mixed $time.
    *	@return object.
    **/
    public function setDefualtFormatTime($time)
    {
        if(!$time)
        {
            $this->_defualtFormatTime = $time;
        }    
        return $this;
    }
    
    /**
    *	Get Defualt Format Function.  This function is used get default date format.
    *	@return defualtFormat.
    **/
    public function getDefualtFormat()
    {      
        return  $this->_defualtFormat;
    }
    
    /**
    *	Get Defualt Format Time Function.  This function is used get default time format.
    *	@return defualtFormatTime.
    **/
    public function getDefualtFormatTime()
    {      
        return  $this->_defualtFormatTime;
    }
    
    /**
    *	Get Defualt Format API Function. This function is used to get default date format for API.
    *	@return defualtFormatAPI.
    **/
    public function getDefualtFormatAPI()
    {
        return $this->_defualtFormatAPI;
    }

    /**
    *	Get Formatted Date Function. This function is used to get formatted date format for system with timezone.
    *	@param mixed $date.
    *   @param mixed $format.
    *   @param mixed $formatRequire.   
    *	@return timezone_datetime|null.
    **/
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
    
    /**
    *	Get Formatted Date To Gmt Function. This Function is used to get formatted date format for system with gmt.
    *   @param mixed $date.
    *   @param mixed $format.
    *   @param mixed $formatRequire.   
    *   @return timezone_datetime|null.
    **/
    public function getFormattedDateToGmt($date, $format = null, $formatRequire = null)
    {
      
        if(!self::isDate($date, $formatRequire))
        {
          
            return null;
        }        
        if($format)
        {
            return date($format, strtotime($date) - $this->_getTimezoneModel()->getTimezoneOffset());    
        }
		
        $timezone_datetime = date($this->getDefualtFormat(), strtotime($date) - $this->_getTimezoneModel()->getTimezoneOffset());
        
        return $timezone_datetime;       
    }
    
    
    /**
    *	Get Formatted Time Function. This function is used to get formatted date format for system with timezone.
    *   @param mixed $date.
    *   @param mixed $format.
    *   @param mixed $formatRequire.   
    *   @return timezone_time|null.
    **/
    public function getFormattedTime($time, $format = null, $formatRequire = null)
    {
        if(!self::isTime($time, $formatRequire))
        {
            return null;
        }
        
        if($format)
        {
            return date($format, strtotime($time) + $this->_getTimezoneModel()->getTimezoneOffset());    
        }        
        $timezone_time = date($this->getDefualtFormatTime(), strtotime($time) + $this->_getTimezoneModel()->getTimezoneOffset());
        
        return $timezone_time;
         
    }
    
    /**
    *	Get Formatted Time To Gmt Function. This function is used to get formatted date format for system with gmt.
    *   @param mixed $date.
    *   @param mixed $format.
    *   @param mixed $formatRequire.   
    *   @return timezone_time|null.
    **/
    public function getFormattedTimeToGmt($time, $format = null, $formatRequire = null)
    {
        if(!self::isTime($time, $formatRequire))
        {
            return null;
        }
        
        if($format)
        {
            return date($format, strtotime($time)- $this->_getTimezoneModel()->getTimezoneOffset());    
        }        
        $timezone_time = date($this->getDefualtFormatTime(), strtotime($time)- $this->_getTimezoneModel()->getTimezoneOffset());
        
        return $timezone_time;     
        
    }
    
    /**
    *	Get Formatted Date API Function. This function is used to get API formatted date.
    *   @param mixed $date.
    *	@return date|null.
    **/
    public function getFormattedDateAPI($date)
    {
        if(!self::isDate($date))
        {
            return null;
        }
        
       return (string)date(self::getDefualtFormatAPI(), strtotime($date));
    }
    
    /**
    *	Get Formatted Date Web Function. This function is used to get API formatted date for system without time zone.
    *   @param mixed $date.
    *   @return date|null.
    **/
    public function getFormattedDateWeb($date)
    {
        if(!self::isDate($date, self::getDefualtFormatAPI()))
        {
            return null;
        }
        
       return (string)date(self::getDefualtFormat(), strtotime($date));
    }
    
    /**
    *	Is Date Function. This function is used to check the date is valid or not.
    *   @param mixed $date.
    *   @param mixed $format.   
    *   @return true|false.
    **/
    public function isDate($date, $format=null)
    {
    
        if(strtotime($date))
        {
            if($format)
            {
                if(date($format, strtotime($date)) == $date)
                {
                    return true;
                }
            }
            else
            {
                if(date(self::getDefualtFormat(), strtotime($date)) == $date)
                {
                    return true;
                }
            }            
        }        
        return false;
    }
    
    /**
    *	Is Time Function. This function is used to check the time is valid or not
    *   @param mixed $date.
    *   @param mixed $format.   
    *   @return true|false.
    **/
    public function isTime($date, $format=null)
    {
        if(strtotime($date))
        {
            if($format)
            {
                if(date($format, strtotime($date)) == $date)
                {
                    return true;
                }				
				return false;
            }            
			return true;        
        }
        return false;
    }
    
    /**
    *	Compare Date Function. This function is used to compare two dates
    *   @param mixed $dateFirst.
    *   @param mixed $dateSecond.   
    *   @return comparision.
    **/
    public function compareDate($dateFirst, $dateSecond)
    {
        if(self::isDate($dateFirst) && self::isDate($dateSecond))
        {
            if(strtotime($dateFirst) > strtotime($dateSecond))
            {
                return 'greater';
            }
            elseif(strtotime($dateFirst) == strtotime($dateSecond))
            {
                return 'equal';
            }
            elseif(strtotime($dateFirst) < strtotime($dateSecond))
            {
                return 'less';
            }
        }
        
        return 'invalid';
    }
	
	/**
    *	Compare Time Function. This function is used to compare two times
    *   @param mixed $timeFirst.
    *   @param mixed $timeSecond.   
    *   @return comparision.
    **/
    public function compareTime($timeFirst, $timeSecond)
    {
        if(self::isTime($timeFirst) && self::isTime($timeSecond))
        {
            if(strtotime($timeFirst) > strtotime($timeSecond))
            {
                return 'greater';
            }
            elseif(strtotime($timeFirst) == strtotime($timeSecond))
            {
                return 'equal';
            }
            elseif(strtotime($timeFirst) < strtotime($timeSecond))
            {
                return 'less';
            }
        }
        
        return 'invalid';
    }
    
    public function getFormattedDateView($date, $format = null)
    {
        if(!self::isDate($date))
        {
            return null;
        }
        $date = new Zend_Date(strtotime($date));
        $date->addTimestamp($this->_getTimezoneModel()->getTimezoneOffset());
        
        if($format)
        {
            return $date->get($format);
        }
        
        return (string)$date;
    }
    
    public function getFormattedTimeView($time, $format = null)
    {
        if(!self::isTime($time))
        {
            return null;
        }
        $date = new Zend_Date();
        $date->set($time,Zend_Date::TIMES);
        $date->addTimestamp($this->_getTimezoneModel()->getTimezoneOffset());     
        if($format)
        {
            return $date->get($format);
        }
        return $date->get(Zend_Date::TIME_SHORT);  
    }
    
    protected function _getTimezoneModel()
    {
        return Ccc::getModel('core/timezone');
    }	
}