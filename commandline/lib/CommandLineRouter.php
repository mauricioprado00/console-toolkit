<?php

class CommandLineRouter
{
    public function __construct()
    {
        var_dump("load routing data, modules and controllers, and aliases");
    }
    
    public function match(CommandLineRequest $request)
    {
        $request->setController("some");
        $request->setModule("some");
        $request->setAction("some");
        echo "routing";
        return true;
    }
}