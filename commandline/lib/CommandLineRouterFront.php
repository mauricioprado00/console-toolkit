<?php

class CommandLineRouterFront
{
    /**
     * @var array CommandLineRouterStandard
     */ 
    private $_routers;
    
    private $_eventListeners;
    
    public function __construct()
    {
        $this->_eventListeners = array(
            'dispatchBefore' => array(),
            'dispatch' => array(),
            'dispatchAfter' => array()
        );
    }
    
    /**
     * @param CommandLineRequest $request
     */
    public function dispatch(CommandLineRequest $request)
    {
        foreach ($this->_routers as $router) {
            if ($router->match($request)) {
                break;
            }
        }
        
        $this->dispatchEventBefore($request);
        $this->dispatchEvent($request);
        $this->dispatchEventAfter($request);
        return true;
    }
    
    /**
     * @param CommandLineRequest $request
     */
    public function dispatchEventBefore(CommandLineRequest $request)
    {
        return $this->_notifyEvent("dispatchBefore", array('request' => $request));
    }
    
    /**
     * @param CommandLineRequest $request
     */
    public function dispatchEvent(CommandLineRequest $request)
    {
        return $this->_notifyEvent("dispatch", array('request' => $request));
    }
    
    /**
     * @param CommandLineRequest $request
     */
    public function dispatchEventAfter(CommandLineRequest $request)
    {
        return $this->_notifyEvent("dispatchAfter", array('request' => $request));
    }
    
    public function addDispatchBeforeListener($callback)
    {
        return $this->_addListener("dispatchBefore", $callback);
    }
    
    public function addDispatchListener($callback)
    {
        return $this->_addListener("dispatch", $callback);
    }
    
    public function addDispatchAfterListener($callback)
    {
        return $this->_addListener("dispatchAfter", $callback);
    }
    
    private function _addListener($eventName, $callback)
    {
        return $this->_eventListeners[$eventName][] = $callback;
    }
    
    private function _notifyEvent($eventName, $eventData)
    {
        foreach ($this->_eventListeners[$eventName] as $callback) {
            call_user_func_array($callback, $eventData);
        }
    }
    
    /**
     * @return CommandLineRouter
     */
    public function addRouter($name, $router)
    {
        $router->setFront($this);
        $this->_routers[$name] = $router;
        return $this;
    }
}