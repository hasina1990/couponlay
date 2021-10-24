<?php

class Website_Model_Website_Row_Validation extends Core_Model_Table_Row_Validation_Abstract
{
    public function hasValidValue($key)
    {
        $row = $this->getRowClassObject(); 
        
        switch($key)
        {                
            case 'category_id':
                return $this->_validateCategoryId($key, $row);
                break;
                
            case 'name':
                return $this->_validateName($key, $row);
                break;
                
            case 'website_url':
                return $this->_validateWebsiteUrl($key, $row);
                break;
                            
            case 'email':
                return $this->_validateEmail($key, $row);
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
    
    protected function _validateCategoryId($key, $row)
    {
        if(!is_array($row->{$key}))
        {
            $this->setError($key, 'Please select category.');
            return false;
        }
        elseif(!count($row->{$key}))
        {
            $this->setError($key, 'Please select category.');
            return false;
        }
        
        $row->{$key} = implode(",", $row->{$key});
        return true;
    }
           
    protected function _validateName($key, $row)
    {    
        $valid = new Zend_Validate_NotEmpty();
        if(!$valid->isValid(($row->{$key})) )
        {  
            $this->setError($key, 'Please enter valid website name.');
            return false;
        }
        return true;
    }
   
    protected function _validateWebsiteUrl($key, $row)
    {    
        $valid = new Zend_Validate_NotEmpty();
        if(!$valid->isValid(($row->{$key})) )
        {  
            $this->setError($key, 'Please enter valid website url.');
            return false;
        }
        return true;
    }
    
    protected function _validateEmail($key, $row)
    {   
        if($row->{$key})
        { 
            $valid = new Zend_Validate_EmailAddress();
            if(!$valid->isValid(($row->{$key})) )
            {  
                $this->setError($key, 'Please enter valid email.');
                return false;
            }
        }
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
        if(!$this->getRowClassObject()->website_id)
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