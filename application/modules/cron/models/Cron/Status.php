<?php
class Cron_Model_Cron_Status
{
    const STATUS_ENABLED_YES = 1;
    const STATUS_ENABLED_NO = 2;
     
    const STATUS_ENABLED_YES_TEXT = "Enable";
    const STATUS_ENABLED_NO_TEXT = "Disable";
    
    /**
    *    Get Is Enabled Options Function. This function is used to get options for admin is Enabled or not.
    *    @return options.
    **/
    public function getOptions()
    {
        $options = array(
                self::STATUS_ENABLED_NO    =>    self::STATUS_ENABLED_NO_TEXT,
                self::STATUS_ENABLED_YES => self::STATUS_ENABLED_YES_TEXT
        );
       
       return $options;
    }
}