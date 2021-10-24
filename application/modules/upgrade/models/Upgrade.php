<?php
class Upgrade_Model_Upgrade extends Core_Model_Table_Abstract
{
    protected $_name = 'system_database_upgrade';
    protected $_primary = 'upgrade_id';
    protected $_rowClass = 'Upgrade_Model_Upgrade_Row';
    protected $_rowsetClass = 'Upgrade_Model_Upgrade_Rowset';
    
    const DB_PENDING = "pending";
    const DB_PROCESSING = "processing";
    const DB_COMPLETED = "completed";
    
	
}