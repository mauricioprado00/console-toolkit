<?php

class CommandLineRouterStandard extends CommandLineRouterAbstract
{
    /**
     * Tries to match a request
     * @return boolean true if there is a match
     */
    public function match(CommandLineRequest $request)
    {
        // recorrer modulos (http://svn.magentocommerce.com/source/branches/1.7/app/code/core/Mage/Core/Controller/Varien/Router/Standard.php)
        // buscar actions yhacer el match y dispatch
        $request->setController("some");
        $request->setModule("some");
        $request->setAction("some");
        echo "routing " . __FILE__ . __LINE__ . "\n";
        return true;
    }
}