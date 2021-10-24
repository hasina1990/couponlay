<?php

class Cron_Model_Cron_Schedule_Rowset extends Core_Model_Table_Rowset_Abstract
{
    /**
    *	$_rowClass protected Variable. It contains row class name for Cron Schedule model.
    *	@var mixed
    **/
    protected $_rowClass = 'Cron_Model_Cron_Schedule_Row';
	
    /**
    *	$_tableClass protected Variable. It contains Cron Schedule model class name.
    *	@var mixed
    **/
    protected $_tableClass = 'Cron_Model_Cron_Schedule';
}	

