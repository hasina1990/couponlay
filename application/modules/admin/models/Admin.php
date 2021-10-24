<?php

class Admin_Model_Admin extends Core_Model_Table_Abstract

{

	protected $_name = 'admin';

	protected $_primary   = 'admin_id';

    protected $_rowClass = 'Admin_Model_Admin_Row';

	protected $_rowsetClass = 'Admin_Model_Admin_Rowset';

	

    const DEFAULT_EMAIL     = "ansari.hasina@gmail.com";

    const DEFAULT_PASSWORD  = "123456";

    

    public function getUserName()

    {

        $login = Ccc::getSingleton('admin/login')->getLogin();

        return $login->email;

    }

    /**

    *    Generate New Password Function. This function is used to generate New Password for Admin.

    *    @return password.

    **/

    public function generateNewPassword()

    {

        return time();

    }

	public function encryptPassword($password)

	{

		return md5($password);

	}

    public function validatePassword($password)

    {

        $validator = new Zend_Validate_StringLength(6,20);

        if(!$validator->isValid($password))

        {

            return false;

        }

        return true;

    }

    

}