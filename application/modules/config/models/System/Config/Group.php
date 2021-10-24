<?php
    
class Config_Model_System_Config_Group extends Core_Model_Table_Abstract
{
    protected $_name = 'system_config_group';
    protected $_primary   = 'system_config_group_id';
    protected $_rowClass = 'Config_Model_System_Config_Row';
    protected $_rowsetClass = 'Config_Model_System_Config_Rowset';
    protected $_systemGroupOptions = array();
    const CACHE_CODE = 'system_config_group'; 
    
    public function getSystemGroups()
    {
        $systemGroups = $this->fetchAll();
        foreach($systemGroups as $_group)
        {
            $this->_systemGroupOptions[$_group->system_config_group_id] = $_group->name;
        }
        return $this->_systemGroupOptions;
    }
    
    protected function _getDateModel()
    {
        return Ccc::getModel('admin/date');
    }    
    
    protected function _getTimezoneModel()
    {
        return Ccc::getModel('admin/timezone');        
    }
}