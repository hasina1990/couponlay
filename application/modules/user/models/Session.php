<?php
/**
*	Ccc_Front_Model_Session
**/
class User_Model_Session extends Core_Model_Session
{
	/**
    *	$_namespace protected Variable. Used to Store the Namespace for session.
	*	@var mixed
    **/
	protected $_namespace = 'etms_front';
    
	/**
	*	Get Session Id Function. This funciton used to get Session Id of current session.
	*	@return session id.
	**/
    public function getSessionid()	
    {
        return session_id();
    }
}

