<?php
/*echo crypt('master', base64_encode('master'));
die;*/

ini_set('memory_limit', '256M');

defined('APPNAME') || define('APPNAME', 'etms');
defined('TABLE_PREFIX') || define('TABLE_PREFIX', '');
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('APPLICATION') || define('APPLICATION', 'application');

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) .DS. APPLICATION));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'local'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once 'Zend/Application.php';

final class Ccc
{
	protected static $_registry = array();
	
    public static function getBaseDir($subpath = null)
    {
       return realpath(APPLICATION_PATH . '/../'.$subpath);
    }
    
	public function registry($key)
	{		
		if(isset(self::$_registry[$key]))
		{
			return self::$_registry[$key];
		}		
		return NULL;
	}
	
	public static function register($key, $value)
	{
		self::$_registry[$key] = $value;
		return true;
	}
	
	public static function unregister($key)
	{
		if(isset(self::$_registry[$key]))
		{
			unset(self::$_registry[$key]);
		}
		return true;
	}
	
	public static function getSingleton($model=NULL)
	{
		if($model != NULL)
		{
			$key = '__singleton/'.self::createClassNameModel($model);
            
			if(self::registry($key))
			{				
				return self::registry($key);
			}
			else
			{
				$instance = self::getInstance(self::createClassNameModel($model));
                self::register($key, $instance);
				return $instance;
			}	
		}
		return NULL;
	}
	
	public function getInstance($class)
	{
		return $instance = new $class();
	}
	
    public static function getBlock($block=NULL)
    {         
        if($block != NULL)
        {
            $block_className = self::createClassNameBlock($block);
            $instance = self::getInstance($block_className);
            $instance->setModulePath(self::getClassModuleName($block));
            return $instance;
        }
        return NULL;
    }
    
    public function createClassNameBlock($block)
    {
        $blockArray = explode("/", $block);
        if(count($blockArray)==2)
        {        
            $blockArrayTmp[] = ucfirst($blockArray[0]);
            $blockArrayTmp[] = 'View_Block';
            $blockArrayTmp[] =  implode("_", array_map("ucfirst", explode("_", $blockArray[1])));
            
            return implode("_", $blockArrayTmp);
        }
        else if(count($blockArray)==1)
        {   
            $blockArrayTmp[] = ucfirst($blockArray[0]);
            $blockArrayTmp[] = 'View_Block';
            $blockArrayTmp[] =  ucfirst($blockArray[0]);
            
            return implode("_", $blockArrayTmp);
        }
        return $block;
    }
    
    public function getClassModuleName($param)
    {
        $paramArray = explode("/", $param);
        if(count($paramArray)==2)
        {        
            return strtolower($paramArray[0]);
        }
        else if(count($blockArray)==1)
        {            
            return strtolower($paramArray[0]);            
        }
        return $param;
    }
    
    
	public static function getHelper($helper=NULL)
	{
        if($helper != NULL)
		{
			return self::getInstance(self::createClassNameHelper($helper));
		}
		return NULL;
	}
	
	public function createClassNameHelper($helper)
	{
		$helperArray = explode("/", $helper);
		if(count($helperArray)==2)
		{
			$helperArrayTmp[] = ucfirst($helperArray[0]);
			$helperArrayTmp[] = 'View_Helper';
			$helperArrayTmp[] =  implode("_", array_map("ucfirst", explode("_", $helperArray[1])));
			
			return implode("_", $helperArrayTmp);
		}
		else if(count($helperArray)==1)
		{
			$helperArrayTmp[] = ucfirst($helperArray[0]);
			$helperArrayTmp[] = 'View_Helper';
			$helperArrayTmp[] =  ucfirst($helperArray[0]);
			
			return implode("_", $helperArrayTmp);
		}
		
		return $helper;
	}
	
	
	public static function getModel($model=NULL)
	{
        if($model != NULL)
		{
			return self::getInstance(self::createClassNameModel($model));
		}
        
		return NULL;
	}
	
	public function createClassNameModel($model)
	{
		$modelArray = explode("/", $model);
		if(count($modelArray)==2)
		{
			$modelArrayTmp[] = ucfirst($modelArray[0]);
			$modelArrayTmp[] = 'Model';
			$modelArrayTmp[] =  implode("_", array_map("ucfirst", explode("_", $modelArray[1])));
			return implode("_", $modelArrayTmp);
		}
		else if(count($modelArray)==1)
		{
			$modelArrayTmp[] = ucfirst($modelArray[0]);
			$modelArrayTmp[] = 'Model';
			$modelArrayTmp[] =  ucfirst($modelArray[0]);
			
			return implode("_", $modelArrayTmp);
		}
		
		return $model;
	}
	
	public function run()
	{ 
		require_once 'Zend/Application.php';

		// Create application, bootstrap, and run
		$application = new Zend_Application(
			APPLICATION_ENV,
			APPLICATION_PATH . '/configs/application.ini'
		);
		
		$application->bootstrap()->run();
	}
	
	public static function bootstrap()
	{
		// Create application, bootstrap, and run
		$application = new Zend_Application(
			APPLICATION_ENV,
			APPLICATION_PATH . '/configs/application.ini'
		);
		
		$application->bootstrap();
	}
}
?>