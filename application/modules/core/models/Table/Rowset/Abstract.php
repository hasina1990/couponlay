<?php
/**
*	Ccc_Core_Model_Table_Rowset_Abstract
**/
class Core_Model_Table_Rowset_Abstract extends Zend_Db_Table_Rowset_Abstract
{
    /**
    *	$_ids protected Variable. Used to store all ids.
    *	@var array
    **/
	protected $_ids = array();
	
    /**
    *	Get All Ids Function. Used to get all ids(primary key) from database table.
    *   @return ids.
    **/
	public function getAllIds()
	{
		if(!$this->_ids)
		{
			if($this->count())
			{
				for($cnt=0; $cnt<$this->count(); $cnt++)
				{
					$this->_ids[] = $this->getRow($cnt)->{(string)$this->getTable()->getPrimary()};
				}
			}
		}		
		return $this->_ids;
	}
	
	/**
	*	_getDateModel Function. Used to get core date model object.
	*	@return object.
	**/
	protected function _getDateModel()
	{
		return Ccc::getModel('core/date');
	}
	
	/**
	*	_getTimezoneModel Function. Used to get core timezone model object.
	*	@return object.
	**/
	protected function _getTimezoneModel()
	{
		return Ccc::getModel('core/timezone');
	}
}