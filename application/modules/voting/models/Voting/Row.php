<?php
class Voting_Model_Voting_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Voting_Model_Voting';
    protected $_validationClassModel = 'voting/voting_row_validation';
}