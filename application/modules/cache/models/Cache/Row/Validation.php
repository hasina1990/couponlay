<?php
/**
*	Cache_Model_Cache_Row
**/
class Cache_Model_Cache_Row_Validation extends Core_Model_Table_Row_Validation_Abstract
{
    public function hasValidValue($key)
    {
        $row = $this->getRowClassObject();
        
        switch($key)
        {
            case 'name':
                return $this->_validateName($key, $row);
                break;
                
            case 'prefix':
            case 'code':
            case 'tag':
                return $this->_validatePrefixCodeTag($key, $row);
                break;
                
            case 'lifetime':
                return $this->_validateLifetime($key, $row);
                break;
                
            default:
                break;
        }
        
        return parent::hasValidValue($key);
    }
    
    protected function _validateName($key, $row)
    {
        $validator = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));        
        if(!$validator->isValid($row->{$key})) 
        {
            $this->setError($key, 'Please enter valid name.');
            return false;
        }
        
        $value = trim($row->{$key});
        if(strlen($value) != strlen($row->{$key}))
        {            
            $this->setError($key, 'Please enter valid name.');
            return false;
        }
        return true;
    }
    
    protected function _validatePrefixCodeTag($key, $row)
    {
        $validator = new Zend_Validate_NotEmpty();        
        if(!$validator->isValid($row->{$key})) 
        {
            $this->setError($key, "Please enter valid {$key}.");
            return false;
        }
        
        $value = trim($row->{$key});
        if(strlen($value) != strlen($row->{$key}))
        {            
            $this->setError($key, "Please enter valid {$key}.");
            return false;
        }
        return true;
    }
    
    protected function _validateLifetime($key, $row)
    {
        $validator = new Zend_Validate_Digits();                
        if(!$validator->isValid($row->{$key}))
        {
            $this->setError($key, "Please enter valid lifetime.");
            return false;
        }
        return true;
    }
}