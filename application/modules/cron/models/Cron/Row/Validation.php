<?php
/**
*	Cache_Model_Cache_Row
**/
class Cron_Model_Cron_Row_Validation extends Core_Model_Table_Row_Validation_Abstract
{
    public function hasValidValue($key)
    {
        $row = $this->getRowClassObject();
        
        switch($key)
        {
            case 'job_code':
                return $this->_validateCode($key, $row);
                break;
                
            case 'model':
                return $this->_validateModel($key, $row);
                break;
                
            case 'cron_expr':
                return $this->_validateCronExpr($key, $row);
                break;
                
            default:
                break;
        }
        
        return parent::hasValidValue($key);
    }
    
    protected function _validateCode($key, $row)
    {
        $validator = new Zend_Validate_Alnum();
        if(!$validator->isValid($row->{$key})) 
        {
            $this->setError($key, 'Please enter alpha-numeric job code.');
            return false;
        }
        
        $model = Ccc::getModel('cron/cron');
        
        $select = $model->select()
                  ->where('job_code = ?', $row->{$key});
        if($row->cron_id)
            $select->where('cron_id != ?', $row->cron_id);
            
        $records = $model->fetchAll($select)->toArray();
        
        if(count($records))
        {
            $this->setError($key, "Duplicate job code entry.");
            return false;
        }
        
        return true;
    }
    
    protected function _validateModel($key, $row)
    {
       $validator = new Zend_Validate_NotEmpty();        
        if(!$validator->isValid($row->{$key})) 
        {
            $this->setError($key, "Please enter valid {$key}.");
            return false;
        }
        
        $model = Ccc::getModel('cron/cron');
        
        $select = $model->select()
                  ->where('model = ?', $row->{$key});
        if($row->cron_id)
            $select->where('cron_id != ?', $row->cron_id);
            
        $records = $model->fetchAll($select)->toArray();
        
        if(count($records))
        {
            $this->setError($key, "Duplicate model entry.");
            return false;
        }
        return true;
    }
    
    protected function _validateCronExpr($key, $row)
    {
        $e = preg_split('#\s+#', $row->{$key}, null, PREG_SPLIT_NO_EMPTY);
        
        if (sizeof($e)<5 || sizeof($e)>6) {
            $this->setError($key, 'Invalid cron expression: '.$row->{$key});
            return false;
        }
        return true;
    }
}