<?php
class Banner_Model_Banner_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Banner_Model_Banner';
    protected $_validationClassModel = 'banner/banner_row_validation';
    
    public function getBannerUrl()
    {
        return Ccc::getModel("banner/uploader")->setBanner($this)->getBannerImageUrl();
    }
}