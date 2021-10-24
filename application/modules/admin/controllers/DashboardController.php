<?php
/**
*	Admin_DashboardController
**/
class Admin_DashboardController extends Ccc_Controller_Action_Admin
{
	/**
	*	Index Action. This function is used to show the dashboard.
	*	@return void.
	**/
    public function indexAction()
    {
		$this->_setTitle('Dashboard')->_setActiveTab('dashboard');
      
    }
}