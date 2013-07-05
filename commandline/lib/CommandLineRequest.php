<?php

class CommandLineRequest
{
    private $_module;
    private $_controller;
    private $_action;
    
    public function __construct()
    {
        //
        var_dump($GLOBALS["argv"], __FILE__ . __LINE__);
    }
    
    public function setModule($module)
    {
        $this->_module = $module;
    }
    
    public function getModule()
    {
        return $this->_module;
    }
    
    public function setController($controller)
    {
        $this->_controller = $controller;
    }
    
    public function getController()
    {
        return $this->_controller;
    }   
    
    public function setAction($action)
    {
        $this->_action = $action;
    }
    
    public function getAction()
    {
        return $this->_action;
    }   
}