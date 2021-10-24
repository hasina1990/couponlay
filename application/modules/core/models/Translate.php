<?php
/**
*	Ccc_Core_Model_Translate
**/
class Core_Model_Translate extends Core_Model_Abstract
{
    /**
    *	$_languageKey protected Variable. Used to store value of translation language.
    *	@var string.
    **/
	protected $_languageKey = 'lang';
	
	/**
	*	Get Controller Function. This function is used to get Controller Name.
	*	return Controller Name.
	**/
	public function getController()
	{
		if(!$this->_controller)
		{
			throw new Core_Model_Exception("Invalid controller specified.");
		}
		return $this->_controller;
	}
	
	/**
	*	Set Controller Function. This function is used to set Controller Name.
	*   @param string $controller.
    *	return void.
	**/
	public function setController($controller)
	{
		$this->_controller = $controller;
		return $this;
	}
	
	/**
	*	Process Language Function. This function is used to get translation Language.
	*	return void.
	**/
	public function processLanguage()
	{
		$this->getTranslate();
		return $this;
	}
	
	/**
	*	Get Locale File Path Function. This function is used to get Locale File Path.
	*	return url.
	**/
	protected function getLocaleFilePath()
	{
        return APPLICATION_PATH."/../data/locales/".$this->getRequest()->getModuleName();		
	}
	
	/**
	*	Translate Function. This function is used to translate.
	*   @param string $key. 
    *	return void.
	**/
	public function translate($key)
	{
		$key = trim($key);
		if($this->getTranslate())
		{		
			return $this->getTranslate()->translate($key);
		}
		return $key;
	}
	
	/**
	*	Get Translate Function. This function is used to get object of Zend Translate.
	*	return Translate.
	**/
	public function getTranslate()
	{                    
		if(Zend_Registry::isRegistered ('Zend_Translate'))
		{
			return Zend_Registry::get('Zend_Translate');
		}
		return false;
	}
	
	/**
	*	Get Locale Function. This function is used to get object of Zend Locale.
	*	return Locale.
	**/
	public function getLocale()
	{
		if(Zend_Registry::isRegistered ('Zend_Locale'))
		{
			return Zend_Registry::get('Zend_Locale');
		}
		return false;
	}
	
	/**
	*	Set Translate Function. This function is used to set Translate Language.
	*	return translate.
	**/
	public function setTranslate()
	{                                        
		if(!$language = $this->getRequest()->getParam($this->getLanguageKey()))
		{
			if(!$language = $this->getSession()->getSession($this->getLanguageKey()))
			{
				$language = Ccc::getModel("config/system_config")->getSystemConfig('general/locale/language');	
			}
		}		
		$this->getSession()->setSession($this->getLanguageKey(), $language);
		
		$locale = Ccc::getSingleton('core/locale');
		$translate = new Zend_Translate('csv', $this->getLocaleFilePath()."/{$language}/{$language}.csv", $language);
		
		$locale->setLocale($language);
		$translate->setLocale($locale);
		
		Zend_Registry::set('Zend_Locale', $locale);
		Zend_Registry::set('Zend_Translate', $translate);
				
		return $translate;
	}
	
	/**
	*	Get Front Function. This function is used to get Instance of Front end.
	*	return Instance.
	**/
	public function getFront()
	{
		return Zend_Controller_Front::getInstance();
	}
	
	/**
	*	Get Request Function. This function is used to get object of Request.
	*	return object.
	**/
	public function getRequest()
	{
		return Zend_Controller_Front::getInstance()->getRequest();
	}
	
	/**
	*	Get Session Function. This function is used to get Singleton of core session.
	*	return Singleton.
	**/
	public function getSession()
	{
		return Ccc::getSingleton("core/session");
	}
	
	/**
	*	Get Language Key Function. This function is used to get Language Key.
	*	return key.
	**/
	public function getLanguageKey()
	{
		return $this->_languageKey;
	}
}