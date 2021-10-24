<?php
class Admin_Model_Admin_Status
{
	const ENABLED_YES = 1;
	const ENABLED_NO  = 2;
    
    const ENABLED_YES_TEXT   = 'Yes';
    const ENABLED_NO_TEXT    = 'No';
    
    /**
    *    Get Is Enabled Options Function. This function is used to get options for admin is Enabled or not.
    *    @return options.
    **/
    public function getOptions()
    {
        $options = array(
                self::ENABLED_YES => self::ENABLED_YES_TEXT,
                self::ENABLED_NO    =>    self::ENABLED_NO_TEXT
        );
       
       return $options;
    }
}