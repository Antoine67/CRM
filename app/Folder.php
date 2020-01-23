<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder implements \JsonSerializable
{
    protected $_props;

    /**
    * Construct a new Folder
    *
    * @param array $pr$_propsopDict A list of properties to set
    */
    function __construct($_props = array())
    {
		$this->_props = $_props;
    }

    /**
    * Gets the property dictionary of the Entity
    *
    * @return array The list of properties
    */
    public function getProperties()
    {
        return $this->_props;
    }

    /**
    * Serializes the object by property array
	* Manually serialize DateTime into RFC3339 format
    *
    * @return array The list of properties
    */
    public function jsonSerialize()
    {
        $serializableProperties = $this->getProperties();
        foreach ($serializableProperties as $property => $val) {
            if (is_a($val, "\DateTime")) {
                $serializableProperties[$property] = $val->format(\DateTime::RFC3339);
            } 
        }
        return $serializableProperties;
    }

    public function getChildCount()
    {
        if (array_key_exists("folder", $this->_props) && array_key_exists("childCount", $this->_props['folder'])) {
            return $this->_props['folder']['childCount'];   
        } else return null;
    }

    public function getName()
    {
        if (array_key_exists("name", $this->_props)) {
            return $this->_props["name"];
        } else return null;
    }

    public function getWebUrl()
    {
        if (array_key_exists("webUrl", $this->_props)) {
            return $this->_props["webUrl"];
        } else return null;
    }

    public function getId()
    {
        if (array_key_exists("id", $this->_props)) {
            return $this->_props["id"];
        } else return null;
    }

}
