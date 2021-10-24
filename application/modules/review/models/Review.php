<?php
class Review_Model_Review extends Core_Model_Table_Abstract
{
	protected $_name = 'review';
    protected $_primary = 'review_id';
    protected $_rowClass = 'Review_Model_Review_Row';
    protected $_rowsetClass = 'Review_Model_Review_Rowset';
    
    const APPROVED = 1;
    const PENDNG_APPROVAL = 2;
    const APPROVED_TEXT = 'Approved';
    const PENDNG_APPROVAL_TEXT = 'Pending Approval';
    
}