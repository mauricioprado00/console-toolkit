<?php

abstract class CommandLineRouterAbstract
{
    /** @var CommandLineRouterStandard */
    private $_front = null;
    
    /**
     * sets the front router
     * @return CommandLineRouterStandard
     */
    public function setFront($front)
    {
        $this->_front = $front;
        return $this;
    }
    
    /**
     * Tries to match a request
     * @return boolean true if there is a match
     */
    abstract public function match(CommandLineRequest $request);
}