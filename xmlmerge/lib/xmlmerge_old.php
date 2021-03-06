<?php

class XmlMerge {

    /**
     * @var string source xml file
     */
    private $_source;
    
    /**
     * @var SimpleXMLElement
     */
    private $_current;
    
    /**
     * @var string target xml file
     */
    private $_target;
    
    /**
     * Validates an load the xml file
     *
     * @return SimpleXMLElement
     */
    private function _loadXmlFile($file) 
    {
        if (!file_exists($file)) {
            throw new Exception("The file " . $file . " doesn't exists");
        }
        $extension = substr($file, -4);
        if ($extension !== ".xml") {
            throw new Exception("The file " . $file . " doesn't have the xml extension");
        }
        
        $xml = simplexml_load_file($file);
        if ($xml === false) {
            throw new Exception("The file " . $file . " is not a valid xml file");
        }
        
        return $xml;
    }
    /**
     * Sets the source file to merge with
     * @param string $file
     * @return XmlMerge
     */
    public function setSource($file)
    {
        if ($this->_current = $this->_loadXmlFile($file)) {
            $this->_source = $file;
            if (!isset($this->_target)) {
                $this->setTarget($file);
            }
        }
        return $this;
    }
    
    /**
     * Sets the target file to save
     *
     */
    public function setTarget($file) 
    {
        $this->_target = $file;
    }
    
    /**
     * Saves the merged xml to a target file
     *
     */
    public function save($target = null) 
    {
        if (isset($target)) {
            $this->setTarget($file);
        }
        $this->_save();
    }
    
    public function _save()
    {
        echo "need to be implemented\n";
    }
    
    /**
     * Merges configuration by loading a new xml file
     * @return boolean
     */
    public function mergeByFile($file)
    {
        if ($xml = $this->_loadXmlFile($file)) {
            return $this->mergeByXml($xml);
        }
        
        return false;
    }
    
    /**
     * formats an xml
     * @param string $filename if specified it saves to a file and retuns a boolean
     * @return string | boolean
     */
    public function asXml($filename = null)
    {
        $dom = new DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->_current->asXML());
        $xml = $dom->saveXML();
        if (isset($filename)) {
            return file_put_contents($filename, $xml);
        }
        return $xml;
    }
    
    /**
     * Merges an xml into the current loaded one
     * @param SimpleXMLElement $xml
     * @return boolean
     */
    public function mergeByXml($xml)
    {
        return $this->_mergeXml($this->_current, $xml);
    }
    
    private function _mergeXml($destination, $source)
    {
        $children = $source->children();
        if ($children->count() > 0) {
            foreach ($children as $name => $child) {
                if (!isset($destination->$name)) {
                    if ($child->children()->count() == 0) {
                        $destination->addChild($name, (string) $child);
                    } else {
                        $destination->addChild($name);
                    }
                }
                $this->_mergeXml($destination->$name, $child);
            }
        }
    }
}
