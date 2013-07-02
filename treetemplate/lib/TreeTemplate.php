<?php

class TreeTemplate
{
    /**
     * Template content
     * @var SimpleXMLElement
     */
    private $_templateXml = null;
    
    /**
     * content representing directories and files
     * @var array _template
     */
    private $_template = null;
    
    /**
     * directory where to load resources
     */
    private $_directory = null;
    
    /**
     * @var array _replaces
     */
    private $_replaces = null;
    
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
     * Loads a template
     * 
     */
    public function loadTemplateFromFile($file)
    {
        if ($this->_templateXml = $this->_loadXmlFile($file)) {
            $this->_directory = dirname($file);
            $this->_prepareTemplate();
        }
    }
    
    /** 
     * Processes the xml to generate a simple array
     */
    private function _prepareTemplate()
    {
        $this->_template = array();
        $this->_prepareTemplateDirectory($this->_templateXml->children(), $this->_template);
    }
    
    /**
     * Generates the array structure representation of directories and files
     * @param SimpleXMLElement $childrenXml
     * @param array $array
     */
    private function _prepareTemplateDirectory($childrenXml, &$array) 
    {
        if ($childrenXml->count()) {
            foreach ($childrenXml as $type => $childXml) {
                if ($type === "directory") {
                    $dirname = (string) $childXml->attributes()->name;
                    $array[$dirname] = array();
                    $this->_prepareTemplateDirectory($childXml->children(), $array[$dirname]);
                } elseif ($type === "file") {
                    $filename = (string) $childXml->attributes()->name;
                    $content = null;
                    if (isset($childXml->attributes()->file)) {
                        $resourceFilename = $this->_directory . '/' . ((string) $childXml->attributes()->file);
                        $content = file_get_contents($resourceFilename);
                    } else {
                        $content = (string) $childXml;
                    }
                    $array[$filename] = $content;
                }
            }
        }
    }
    
    /**
     * Set the replaces to make in the template
     * @param array $replaces
     * @return TreeTemplate
     */
    public function setReplaces($replaces)
    {
        $this->_replaces = $replaces;
        return $this;
    }
    
    /**
     * make the replacements
     * @param string $string
     * @return string
     */
    private function _makeReplaces($string)
    {
        $replaces = array();
        foreach ($this->_replaces as $from=>$to) {
            $replaces["[" . $from . "]"] = $to;
        }
        return strtr($string, $replaces);
    }
    
    private function _createDirectory($directory)
    {
        if (is_dir($directory)) {
            return;
        } else {
            $this->_createDirectory(dirname($directory));
            mkdir($directory);
        }
    }
    
    /**
     * Generate content from template using the replacements
     * @param string $directory target directory
     */
    public function generateFromTemplate($directory)
    {
        $this->_createDirectory($directory);
        $this->_generateDirectoryFromTemplate($directory, $this->_template);
    }
    
    private function _generateDirectoryFromTemplate($directory, $array)
    {
        foreach ($array as $name => $content) {
            if (is_array($content)) {
                $subdirectory = $directory . '/' . $this->_makeReplaces($name);
                mkdir($subdirectory);
                $this->_generateDirectoryFromTemplate($subdirectory, $content);
            } else {
                $filename = $directory . '/' . $this->_makeReplaces($name);
                file_put_contents($filename, $this->_makeReplaces($content));
            }
        }
    }
}