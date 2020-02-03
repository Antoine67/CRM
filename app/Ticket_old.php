<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket  implements \JsonSerializable
{
    protected $_props;

    /**
    * Construct a new Ticket
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

    
    public function get($prop) {
        if (array_key_exists($prop, $this->_props)) {
            return $this->_props[$prop];
        } else return null;
    }

    public function getArray($prop) {
        if (array_key_exists($prop, $this->_props) && count($this->_props[$prop]) > 0) {
            return $this->_props[$prop];
        } else return null;
    }

    public function getCreationDate() {
        if (array_key_exists("CREATION_DATE_UT", $this->_props)) {
            return Carbon::create($this->_props["CREATION_DATE_UT"])->format('d/m/Y à H:i');
        } else return null;
    }

}
