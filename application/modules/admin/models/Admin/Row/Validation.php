<?php
/**
*	Admin_Model_Admin_Row_Validation
**/
class Admin_Model_Admin_Row_Validation extends Core_Model_Table_Row_Validation_Abstract
{
    public function hasValidValue($key)
    {
        $row = $this->getRowClassObject();
        
        switch($key)
        {
            case 'first_name':
                return $this->_validateFirstName($key, $row);
                break;            
                
            case 'last_name':
                return $this->_validateLastName($key, $row);
                break;          
           
           case 'is_enabled':
                return $this->_validateIsEnabled($key, $row);
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
    
    protected function _validateFirstName($key, $row)
    {
        if($row->{$key})
        {
            $validator = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
            if(!$validator->isValid($row->{$key})) 
            {
                $this->setError($key, 'Please enter valid first name.');
                return false;
            }
        }
        return true;
    }    
    
    
    protected function _validateLastName($key, $row)
    {
        
        if($row->{$key})
        {
            $validator = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
            if(!$validator->isValid($row->{$key})) 
            {
                $this->setError($key, 'Please enter valid last name.');
                return false;
            }
        }
        return true;
    }
    
    protected function _validateIsEnabled($key, $row)
    {
        $validator = new Zend_Validate_GreaterThan(0);        
        if(!$validator->isValid($row->{$key})) 
        {
            $this->setError($key, 'Please select enabled status.');
            return false;
        }
        return true;
    }
    
    protected function _validateEmail($key, $row)
    {
        $validator = new Zend_Validate_EmailAddress();        
        if(!$validator->isValid($row->{$key})) 
        {
            $this->setError($key, 'Please enter valid email address.');
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
            $this->setError($key, 'Created Date should not exceed the current date and time.');
            return false;
        }
        return true;
    }
    
    protected function _validateUpdatedDate($key)
    {
        if(!$this->getRowClassObject()->admin_id)
        {
            if(!$this->_getDateModel()->isDate($this->getRowClassObject()->{$key}))
            {
                return false;
            }
            else
            {
                $this->setError($key, 'Updated Date should not be exists in created mode.');
                return false;
            }            
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
            $this->setError($key, 'Updated Date should not exceed the current date and time.');
            return false;
        }
        return true;
    }
}