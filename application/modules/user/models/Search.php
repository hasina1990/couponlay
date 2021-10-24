<?php
/**
*	Ccc_User_Model_Search
**/
class User_Model_Search extends Core_Model_Abstract
{
	/**
	*	Get Module Function. This function is used to get Module Name.
	*  	@return Module Name.
	**/
	public function getModule()	
    {
		$front = Zend_Controller_Front::getInstance();		
		return $front->getRequest()->getModuleName();
	}
	
	/**
	*	Get Controller Function. This function is used to get Controller Name.
	*  	@return Controller Name.	
	**/
	public function getController()	
    {
		$front = Zend_Controller_Front::getInstance();		
		return $front->getRequest()->getControllerName();
	}
	
	/**
	*	Get Action Function. This function is used to get Action Name.
	*  	@return Action Name.	
	**/
	public function getAction()	
    {
		$front = Zend_Controller_Front::getInstance();		
		return $front->getRequest()->getActionName();
	}
	
	/**
	*	Get Search Function. This function is used to get Search data.
	*  	@return search data.	
	**/
	public function getSearch()
    {	
		$front = Zend_Controller_Front::getInstance();
		
		if(Ccc::getModel('user/session')->has('search'))
		{
			$search = Ccc::getModel('user/session')->get('search');
		}
		else
		{
			$search = array();
		}
		
		if(isset($search[$this->getModule()][$this->getController()][$this->getAction()]))
		{ 
			return $search[$this->getModule()][$this->getController()][$this->getAction()];
		}
		
		unset($front);
		return $search;
	}
	/**
	*	Set Search Function. This function is used to Set Search data.
	*  	@return object.	
	**/
	public function setSearch()
	{
		if(Ccc::getModel('user/session')->has('search'))
		{
			$search = Ccc::getModel('user/session')->get('search');
            
		}
		else
		{
			$search = array();
		}
	
		$front = Zend_Controller_Front::getInstance();	        	
		if($front->getRequest()->isPost())
		{
			$requestData = $front->getRequest()->getParams();            
            if(isset($requestData["totalItemCount"]))
            {
                $search[$this->getModule()][$this->getController()][$this->getAction()] = $this->setData($requestData);
            }
            else
            {
                $data = array();
                if(isset($search[$this->getModule()][$this->getController()][$this->getAction()]))
                {                
                    $current = $search[$this->getModule()][$this->getController()][$this->getAction()];
                    $data = $current->getData();    
                }
                
               $search[$this->getModule()][$this->getController()][$this->getAction()] = $this->setData(array_merge($data,$requestData));
            }
            
            Ccc::getModel('user/session')->setSession('search', $search);
		}
		else
		{
			$data = array();
			if(isset($search[$this->getModule()][$this->getController()][$this->getAction()]))
			{				
				$current = $search[$this->getModule()][$this->getController()][$this->getAction()];
				$data = $current->getData();	
			}
			
			$search[$this->getModule()][$this->getController()][$this->getAction()] = $this->setData(array_merge($front->getRequest()->getParams(), $data));
			Ccc::getModel('user/session')->set('search', $search);
		}
		
		unset($search);
		unset($front);
		
		return $this;	
	}
    
	/**
	*	Unset Search Function. This function is used to Unset Search data.
	*  	@return void.	
	**/
    public function unsetSearch()
    {   
        if(Ccc::getModel('user/session')->has('search'))
        {  
            $search = Ccc::getModel('user/session')->get('search');
            
           if($search[$this->getModule()][$this->getController()][$this->getAction()])
            {   
                unset($search[$this->getModule()][$this->getController()][$this->getAction()]);
            }           
            Ccc::getModel('user/session')->set('search', $search);            
            
        }
    }	
}
