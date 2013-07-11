<?php

class CommandLineRouterStandard extends CommandLineRouterAbstract
{
    private $_modules = array();
    
    private $_routes = array();
    
    /**
     * Adds a module route
     * @param string $frontName
     * @param string || array $moduleName
     * @param string $routeName
     */
    public function addModule($frontName, $moduleName, $routeName)
    {
        $this->_modules[$frontName] = $moduleName;
        $this->_routes[$routeName] = $frontName;
        return $this;
    }
    
    /**
     * Tries to match a request
     * @return boolean true if there is a match
     */
    public function match(CommandLineRequest $request)
    {
        // recorrer modulos (http://svn.magentocommerce.com/source/branches/1.7/app/code/core/Mage/Core/Controller/Varien/Router/Standard.php)
        // buscar actions yhacer el match y dispatch
        $request->setController("index");
        $request->setModule("customer");
        $request->setAction("index");
        echo "routing " . __FILE__ . __LINE__ . "\n";
        return true;
    }
}