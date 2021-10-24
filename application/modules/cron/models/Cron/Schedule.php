<?php

class Cron_Model_Cron_Schedule extends Core_Model_Table_Abstract
{
    /**
    *	$_name protected Variable. It contains database table name for Cron Schedule model.
    *	@var mixed
    **/
	protected $_name        = 'cron_schedule';
	
    /**
    *	$_primary protected Variable. It contains primary key of cron_setting table.
    *	@var mixed
    **/
    protected $_primary     = 'schedule_id';
    
    /**
    *	$_rowClass protected Variable. It contains row class name for Cron Setting model.
    *	@var mixed
    **/
    protected $_rowClass    = 'Cron_Model_Cron_Schedule_Row';
	
    /**
    *	$_rowsetClass protected Variable. It contains rowSet class name for Cron Setting model.
    *	@var mixed
    **/
    protected $_rowsetClass = 'Cron_Model_Cron_Schedule_Rowset';
    
    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_SUCCESS = 'success';
    const STATUS_MISSED = 'missed';
    const STATUS_ERROR = 'error';
    
    public function getCollection()
    {
        return $this->fetchAll();
    }
    
    /**  Sets a job to STATUS_RUNNING only if it is currently in STATUS_PENDING. This is used to implement locking for cron jobs.
     * Returns true if status was changed and false otherwise.
     * @return boolean  */
    public function tryLockJob($schedule)
    {
        return $this->trySetJobStatusAtomic($schedule->schedule_id, self::STATUS_RUNNING, self::STATUS_PENDING);
    }
    
    public function trySetJobStatusAtomic($scheduleId, $newStatus, $currentStatus)
    {
        $write = $this->getAdapter();
        $result = $write->update(
            $this->getTableName(),
            array('status' => $newStatus),
            array('schedule_id = ?' => $scheduleId, 'status = ?' => $currentStatus)
        );
        if ($result == 1) {
            return true;
        }
        return false;
    }
        
    
}