<?php

class CommandLineDispatcher
{
    private $_basePaths = array();
    private $_classnames = array();
    
    /**
     * Adds a path
     * @param string $path must be formated like: "...../{!module}/controllers/{!controller}Controller.php"
     */
    public function addBasePath($path, $classname)
    {
        $this->_basePaths[] = $path;
        $this->_classnames[] = $classname;
    }
    
   /**
    * @param CommandLineRequest $request
    */
    public function dispatch($request)
    {
        echo "this is the dispatch listener " . __FILE__.__LINE__ . " \n";
        $module = $this->_camelize($request->getModule());
        $action = $this->_camelize($request->getAction());
        $controller = $this->_camelize($request->getController());
        
        foreach ($this->_basePaths as $idx => $basePath) {
            if ($this->_includeControllerFileName($basePath, $module, $controller)) {
                if ($classname = $this->_getControllerClassname($this->_classnames[$idx], $module, $controller)) {
                    $controllerInstance = new $classname;
                    if ($controllerInstance instanceof CommandLineAction && $controllerInstance->hasAction($action)) {
                        var_dump(array("dispatching", $module, $action, $controller));
                        $controllerInstance->setRequest($request);
                        $request->setDispatched(true);
                        $controllerInstance->dispatch($action);
                        break;
                    }
                }
            }
        }
    }
    
    protected function _camelize($str)
    {
        return str_replace(' ', "_", ucwords(str_replace("_", ' ', $str)));
    }
    
    private function _includeControllerFileName($basePath, $module, $controller)
    {
        $replaces = array(
            "{!module}" => $module,
            "{!controller}" => $controller,
        );
        
        $file = strtr($basePath, $replaces);
        
        if (file_exists($file)) {
            include($file);
            return true;
        }
        return false;
    }
    
    private function _getControllerClassname($classname, $module, $controller)
    {
        $replaces = array(
            "{!module}" => $module,
            "{!controller}" => $controller,
        );
        
        $classname = strtr($classname, $replaces);

        if (class_exists($classname)) {
            return $classname;
        }
        return null;
    }
}