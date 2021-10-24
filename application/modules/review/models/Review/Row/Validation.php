<?php

class Review_Model_Review_Row_Validation extends Core_Model_Table_Row_Validation_Abstract
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
            case 'review':
                return $this->_validateReview($key, $row);
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
        if(!(string)$row->{$key})
        {  
            $this->setError($key, 'Please select valid store.');
            return false;
        }
        
        return true;
    }
    
    protected function _validateName($key, $row)
    {    
        if(!(string)$row->{$key})
        {  
            $this->setError($key, 'Please enter Name.');
            return false;
        }
        
        return true;
    }
    protected function _validateReview($key, $row)
    {    
        if(!(string)$row->{$key})
        {  
            $this->setError($key, 'Please enter Review.');
            return false;
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
        if(!$this->getRowClassObject()->voting_id)
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
}