<?php
/**
*	Ccc_Core_Model_Contacts
**/
class Core_Model_Curl
{
    protected $_params = null;
    protected $_url = null;
    
    public function setParams($params)
    {
        $this->_params = $params;
        return $this;
    }
    
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }
    
    public function getParams()
    {
        return $this->_params;
    }
    
    public function getUrl($url)
    {
        return $this->_url;
    }
    
    public function makeRequest()
    {
        if(!$this->_url)
        {
            throw new Exception('Request Url is not set');
        }
        if(!$this->_params)
        {
            throw new Exception('Request Parameters are not set');
        }
        
        $curl = curl_init($this->_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($curl);
    }
}