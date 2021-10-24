<?php
class Core_Model_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract
{
    /**
    *    $_validateFields protected Variable. Used to validate data.
    *    @var array
    **/
    protected $_validateFields = array();    
    
    /**
    *    $_validationClassModel protected model. Used to validate.
    *    @var array
    **/    
    protected $_validationClassModel = 'core/table_row_validation_abstract';                            
    
    /**
    *    $_errors protected Variable. Used to store error messages.
    *    @var array
    **/
    protected $_errors = array();    
    
     /**
    *    $_errors protected Variable. Used to store error messages.
    *    @var array
    **/
    protected $_isCreated = false;
    
     /**
    *    _isInAPIMode protected Variable. Used to store access mode.  web|cron|api
    *    @var array
    **/
    protected $_accessMode = 'web';
    
    const ACCESS_MODE_WEB = 'web';
    const ACCESS_MODE_API = 'api';
    const ACCESS_MODE_CRON = 'cron';
    
    
    /**
    *   Get Errors Function. Used to get all error  messages.
    *   @return errors.
    **/
    public function setAccessMode($mode)
    {
        if(!in_array($mode, array(self::ACCESS_MODE_WEB, self::ACCESS_MODE_API, self::ACCESS_MODE_CRON)))
        {
            throw new Ccc_Core_Model_Exception('"accesss mode" is not valid.');
        }
    
        $this->_accessMode = $mode;
        return $this;
    }
    
    /**
    *   Get Errors Function. Used to get all error  messages.
    *   @return errors.
    **/
    public function getAccessMode()
    {
        return $this->_accessMode;
    }
    
    /**
    *   Get Errors Function. Used to get all error  messages.
    *   @return errors.
    **/
    public function setErrors($errors)
    {
        $this->_errors = $errors;
        return $this;
    }
    
    /**
    *   Get Errors Function. Used to get all error  messages.
    *   @return errors.
    **/
    public function getErrors()
    {
        return array_values($this->_errors);
    }
    
    /**
    *   Get Errors In Json Format Function. Used to get all error  messages encoded in json format.
    *   @return errors.
    **/
    public function getErrorsInJsonFormat()
    {
        return json_encode(array_values($this->_errors));
    }
    
    /**
    *   Get Errors With Column Function. Used to get all error  messages with column.
    *   @return errors.
    **/
    public function getErrorsWithColumn()
    {
        return $this->_errors;
    }
    
    /**
    *   Get Errors With Column In Json Format Function. Used to get all error  messages with column encoded in Json format.
    *   @return errors.
    **/
    public function getErrorsWithColumnInJsonFormat()
    {
        return json_encode($this->_errors);
    }
    
    /**
    *   validate Function. Used to validate data.
    *   @param string $controller.
    *   @return true|false.
    **/
    public function validate($controller = null)
    {
        if($this instanceof Admin_Model_Admin_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        if($this instanceof Config_Model_System_Config_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        if($this instanceof Config_Model_System_Config_Group_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        if($this instanceof Cache_Model_Cache_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        if($this instanceof Category_Model_Category_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        if($this instanceof Website_Model_Website_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        if($this instanceof Coupon_Model_Coupon_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        if($this instanceof Tag_Model_Tag_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        if($this instanceof User_Model_User_Row)
        {
            return Ccc::getModel($this->getValidationClassModel())->setRowClassObject($this)->validate();
        }
        
        $this->_errors = array();
        
        if($this->_modifiedFields)
        {            
            foreach(array_keys($this->_modifiedFields) as $key)
            {
                if($this->hasValidValue($key))
                {
                    continue;
                }
                
                if(isset($this->_validateFields[$key]))
                {
                    if($controller instanceof Ccc_Controller_Action)
                    {
                        $this->_errors[$key] = $controller->view->translate($this->_validateFields[$key]['message']);
                    }
                    else
                    {
                        $this->_errors[$key] = $this->_validateFields[$key]['message'];
                    }
                }
            }
        }
        
        if($this->_errors)
        {
            return false;
        }
        return true;
    }
    
    /**
    *    Has Valid Value Function. This function is used to check the value is valid or not.
    *   @param mixed $key.
    *   @return true|false.
    **/
    public function hasValidValue($key)
    {
        if($key)
        {    
            if($this->_validateFields)
            {
                if(in_array($key, array_keys($this->_validateFields)))
                {
                    if(isset($this->_validateFields[$key]) && (boolean)$this->_validateFields[$key]['require'] == false)
                    {
                        if(!$this->{$key})
                        {
                            return true;
                        }    
                    }                    
                    
                    $this->{$key} = null;
                    unset($this->_modifiedFields[$key]);
                }
            }        
        }
        return false;
    }
    
    /**
    *    _getDateModel Function. Used to get core date model object.
    *    @return object.
    **/
    protected function _getDateModel()
    {
        return Ccc::getModel('core/date');
    }
    
    /**
    *    getDateModel Function. Used to get core date model object.
    *    @return object.
    **/
    public function getDateModel()
    {
        return $this->_getDateModel();
    }
    
    /**
    *    _getTimezoneModel Function. Used to get core timezone model object.
    *    @return object.
    **/
    protected function _getTimezoneModel()
    {
        return Ccc::getModel('core/timezone');
    }
    
    /**
    *    _getTimezoneModel Function. Used to get core timezone model object.
    *    @return object.
    **/
    public function getTimezoneModel()
    {
        return $this->_getTimezoneModel();        
    }
    
    /**
    *    _before Save Function. Used To save cache data.
    *    @return object.
    **/
    protected function _beforeSave()
    {
        return $this;    
    }
    
    /**
    *    _after Save Function. Used To save cache data.
    *    @return object.
    **/
    protected function _afterSave()
    {
        return $this;
    }
    
    /**
    *    _after Create Function. Used To save cache data.
    *    @return object.
    **/
    protected function _afterCreate()
    {
        return $this;
    }
    
    /**
    *    Save Function.
    *    @return void.
    **/
    public function save()
    {
        if(!$this->{$this->getTable()->getPrimary()})
        {
            $this->_isCreated = true;
        }
        
        $this->_beforeSave();
        $save = parent::save();
        $this->_afterSave();
        
        if($this->_isCreated)
        {
            $this->_afterCreate();        
        }
        return $save;
    }
    
    public function getModifiedFields()
    {
        return $this->_modifiedFields;
    }
    
    public function setModifiedFields($fields)
    {
        $this->_modifiedFields = $fields;
        return $this;
    }
    
    public function getValidationClassModel()
    {
        return $this->_validationClassModel;
    }
    
    public function getData()
    {
        return $this->_data;
    }
    
    public function getURLKey()
    {
        if(isset($this->url_key))
        {
            return Ccc::getModel("core/url_rewrite")->getRequestUrlByIdPath($this);
        }
        
        return "";
    }
    
    public function removeURLKey()
    {
        if(isset($this->url_key))
        {
            return Ccc::getModel("core/url_rewrite")->removeUrlByIdPath($this);
        }
    }
}