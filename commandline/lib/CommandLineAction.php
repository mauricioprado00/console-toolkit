<?php

class CommandLineAction
{
    private $_request;
    private $_response;
    
    public function setRequest($request)
    {
        $this->_request = $request;
        return $this;
    }
    
    public function getRequest()
    {
        return $this->_request;
    }
    
    public function setResponse($response)
    {
        $this->_response = $response;
        return $this;
    }
    
    public function getResponse()
    {
        return $this->_response;
    }
    
    public function hasAction($action)
    {
        return is_callable(array($this, $this->getActionMethodName($action)));
    }
    
    /**
     * Retrieve action method name
     *
     * @param string $action
     * @return string
     */
    public function getActionMethodName($action)
    {
        return $action . 'Action';
    }
    
    public function norouteAction()
    {
       echo "show some help probably " . __FILE__ . __LINE__ . "\n";
    }
    
    public function preDispatch()
    {
        
    }
    
    public function postDispatch()
    {
        
    }
    
    public function dispatch($action)
    {
        try {
            $actionMethodName = $this->getActionMethodName($action);

            if (!is_callable(array($this, $actionMethodName))) {
                $actionMethodName = 'norouteAction';
            }

            $this->preDispatch();

            if ($this->getRequest()->isDispatched()) {
                /**
                 * preDispatch() didn't change the action, so we can continue
                 */
                $this->$actionMethodName();
                $this->postDispatch();
            }
        }
        catch (Mage_Core_Controller_Varien_Exception $e) {
            
        }
    }
}