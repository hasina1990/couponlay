<?php

class Coupon_Model_Coupon_Row_Validation extends Core_Model_Table_Row_Validation_Abstract
{
    public function hasValidValue($key)
    {
        $row = $this->getRowClassObject(); 
        
        switch($key)
        {                
            case 'website_id':
                return $this->_validateWebsiteId($key, $row);
                break;
                
            case 'name':
                return $this->_validateName($key, $row);
                break;
            
            case 'type':
                return $this->_validateType($key, $row);
                break;
            
            case 'code':
                return $this->_validateCode($key, $row);
                break;
            
            case 'start_date':
                return $this->_validateStartDate($key);
                break;
                
            case 'end_date':
                return $this->_validateEndDate($key);
                break;
                            
            case 'created_date':
                return $this->_validateCreatedDate($key);
                break;
                
            case 'updated_date':
                return $this->_validateUpdatedDate($key);
                break; 
           
            default:
                break;
        }
        
        return parent::hasValidValue($key);
    }
    
    protected function _validateWebsiteId($key, $row)
    {
        if(!(int)$row->{$key})
        {
            $this->setError($key, 'Please select website.');
            return false;
        }
       
        return true;
    }
           
    protected function _validateName($key, $row)
    {    
        $valid = new Zend_Validate_NotEmpty();
        if(!$valid->isValid(($row->{$key})) )
        {  
            $this->setError($key, 'Please enter valid coupon name.');
            return false;
        }
        return true;
    }
   
    protected function _validateType($key, $row)
    {
        if(!(int)$row->{$key})
        {
            $this->setError($key, 'Please select Coupon Type.');
            return false;
        }
       
        return true;
    }     
    
    protected function _validateCode($key, $row)
    {
        if(!(int)$row->type)
        {
            $this->setError('type', 'Please select Coupon Type.');
            return false;
        }
        
        if($row->type == Coupon_Model_Coupon::COUPON_TYPE_CODE)
        {
            $row->{$key} = trim($row->{$key});
            if(!strlen($row->{$key}))
            {
                 $this->setError($key, 'Please enter Coupon code.');
                 return false;
            }
        }
        else
        {
            $row->{$key} = NULL;
        }
       
        return true;
    }
    
    protected function _validateCreatedDate($key)
    {
        if(!$this->_getDateModel()->isDate($this->getRowClassObject()->{$key}))
        {
            $this->setError($key, 'Created Date is not valid.');
            return false;
        }
        elseif($this->_getDateModel()->compareDate($this->getRowClassObject()->{$key}, date("Y-m-d H:i:s")) == 'greater')
        {
            $this->setError($key, 'Created Date should not exist the current date and time.');
            return false;
        }
        return true;
    }
    
    protected function _validateUpdatedDate($key)
    {
        if(!$this->getRowClassObject()->coupon_id)
        {
            if(!$this->_getDateModel()->isDate($this->getRowClassObject()->{$key}))
            {
                return false;
            }            
        }
        
        if(!$this->canProcessIfNull($key))
        {
            return false;
        }
        
        if(!$this->_getDateModel()->isDate($this->getRowClassObject()->{$key}))
        {
            $this->setError($key, 'Updated Date is not valid.');
            return false;
        }
        elseif($this->getRowClassObject()->created_date > $this->getRowClassObject()->{$key})
        {
            $this->setError($key, 'Created date must less than updated date.');
            return false;
        }
        elseif($this->_getDateModel()->compareDate($this->getRowClassObject()->{$key}, date("Y-m-d H:i:s")) == 'greater')
        {
            $this->setError($key, 'Updated Date should not exist the current date and time.');
            return false;
        }
        return true;
    }
    
    protected function _validateStartDate($key)
    {
        if(!$this->getRowClassObject()->{$key})
        {
            $this->getRowClassObject()->{$key} = NULL;
        }
        else
        {
            $this->getRowClassObject()->{$key} = date("Y-m-d H:i:s", strtotime($this->getRowClassObject()->{$key}));
        }
        
        if($this->getRowClassObject()->{$key})
        {
            if(!$this->_getDateModel()->isDate($this->getRowClassObject()->{$key}))
            {
                $this->setError($key, 'Start Date is not valid.');
                return false;
            }
        }
        
        return true;
    }
    
    protected function _validateEndDate($key)
    {
        if(!$this->getRowClassObject()->{$key})
        {
            $this->getRowClassObject()->{$key} = NULL;
        }
        else
        {
            $this->getRowClassObject()->{$key} = date("Y-m-d H:i:s", strtotime($this->getRowClassObject()->{$key}));
        }
        
        if($this->getRowClassObject()->{$key})
        {
            if(!$this->_getDateModel()->isDate($this->getRowClassObject()->{$key}))
            {
                $this->setError($key, 'End Date is not valid.');
                return false;
            }
            else
            {
                if($this->getRowClassObject()->start_date)
                {
                    if($this->getRowClassObject()->start_date > $this->getRowClassObject()->{$key})
                    {
                        $this->setError($key, 'Start date must less than End date.');
                        return false;
                    }
                }
            }
        }
        
        return true;
    } 
}