<?php
/**
*	Ccc_Core_Model_Log
**/
class Core_Model_Log
{

    protected $_logPath = 'media/';
    //protected $_logPath = 'data/log/';

    /**
    *	$_file protected Variable. Used to store name of system log file.
    *	@var mixed
    **/
    protected $_file = 'system.log';
	
	/**
	*	Get Log Path Function. generate the log path.
	*	@return @path.
	**/
	public function getLogPath()
	{
		return Ccc::getBaseDir().DIRECTORY_SEPARATOR.$this->_logPath;
	}
	
	/**
	*	Get Log File Function. generate the log File.
	*	@return @filename.
	**/
	public function getLogFile()
	{
		return $this->_file;
	}
	
    /**
    *   Log function.
    *   @param mixed $message.
    *   @param mixed $file.
    *   @return file|object.
    **/
    public function log($message,$file=null)
    {
		$file = trim($file);
		if($file)
		{
			$this->_file = $file.".log";
		}
		$writer = new Zend_Log_Writer_Stream($this->getLogPath().$this->getLogFile());
		$logger = new Zend_Log($writer);
		if(!is_scalar($message))	
		{
			$logger->info(print_r($message,true).PHP_EOL);
		}
		else
		{
			$logger->info($message.PHP_EOL);
		}
		return $this;
    }
}