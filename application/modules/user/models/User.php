<?php
class User_Model_User extends Core_Model_Table_Abstract
{
    protected $_name = 'user';
    protected $_primary = 'user_id';
    protected $_rowClass = "User_Model_User_Row";
    protected $_rowsetClass = 'User_Model_User_Rowset'; 
  
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
}