<?php 
class Core_Model_Routes extends Zend_Controller_Router_Route
{
    public function __construct()
    {
        
    }
    public function getParts($r)
    {
        return $r->_parts;
    }
}