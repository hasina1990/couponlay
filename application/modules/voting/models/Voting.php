<?php
class Voting_Model_Voting extends Core_Model_Table_Abstract
{
	protected $_name = 'voting';
    protected $_primary = 'voting_id';
    protected $_rowClass = 'Voting_Model_Voting_Row';
    protected $_rowsetClass = 'Voting_Model_Voting_Rowset';
    
    const VOTER_ENTITY_STORE = "store";
    const VOTER_ENTITY_COUPON = "coupon";
}