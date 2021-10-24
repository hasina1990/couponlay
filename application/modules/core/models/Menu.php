<?php
class Core_Model_Menu extends Core_Model_Table_Abstract
{
    protected $_name = 'menu';
    protected $_primary   = 'id';
    protected $_rowClass = 'Core_Model_Menu_Row';
	protected $_rowsetClass = 'Core_Model_Menu_Rowset';
    
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
    
    const IS_FOOETR_LINK_YES = 1;
    const IS_FOOETR_LINK_NO  = 2; 
    const IS_FOOETR_LINK_YES_TEXT = 'Yes';
    const IS_FOOETR_LINK_NO_TEXT  = 'No';
    
    const IS_QUICKLINK = 1;
    const IS_ABOUT_US  = 2; 
    const IS_QUICKLINK_TEXT = 'Quick links';
    const IS_ABOUT_US_TEXT  = 'About Us';
}