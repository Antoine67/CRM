<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer implements \JsonSerializable
{
    protected $_props;

    /**
    * Construct a new Customer
    *
    * @param array $pr$_propsopDict A list of properties to set
    */
    function __construct($_props = array())
    {
		$this->_props = $_props;
        $this->_props['folders'] = array();
        $this->_props['extranetFolders'] = array();
        $this->_props['tickets'] = array();

        if (!isset($this->_props['associatedFiles'])) $this->_props['associatedFiles'] = array();

        //Create Object vars from array vars
        if (array_key_exists("_folders", $_props)) {
            foreach($_props['_folders'] as $folder) {
                $this->addFolder(new Folder($folder));
            }
            unset($this->_props["_folders"]);
        }

        if (array_key_exists("_extranetFolders", $_props)) {
           foreach($_props['_extranetFolders'] as $folder) {
                $this->addExtranetFolder(new Folder($folder));
            }
            unset($this->_props["_extranetFolders"]);
        }

        if (array_key_exists("_tickets", $_props)) {
            foreach($_props['_tickets'] as $ticket) {
                $this->addTickets([new Ticket($ticket)]);
            }
            unset($this->_props["_tickets"]);
        }
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

    public function addFolder(Folder $folder) {
        array_push($this->_props['folders'], $folder);
    }

    public function addExtranetFolder(Folder $folder) {
        array_push($this->_props['extranetFolders'], $folder);
    }

    public function addAssociatedFiles($name, $givenName, $path = null, $downloadUrl = null) {
         array_push($this->_props['associatedFiles'], ['name' => $name,'givenName' => $givenName, 'path' => $path, 'downloadUrl' => $downloadUrl]);
    }

    public function addTickets($ticketsArray) {
        $this->_props['tickets'] = array_merge($this->_props['tickets'], $ticketsArray);
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
            }else if (strcmp($property, 'folders') == 0 || strcmp($property,'extranetFolders') == 0 || strcmp($property,'tickets') == 0) {
                //Folders objects are stored under '_folders' vars so they can be treated as Folder instead of array when decoding json -> See __construct
                $serializableProperties["_$property"] = $val;
                $serializableProperties[$property] = null;
            }
        }
        return $serializableProperties;
    }

    public function getId()
    {
        if (array_key_exists("id", $this->_props)) {
            return $this->_props["id"];
        } else return null;
    }

    public function getExtranetId()
    {
        if (array_key_exists("extranetId", $this->_props)) {
            return $this->_props["id"];
        } else return null;
    }

    public function getName()
    {
        if (array_key_exists("name", $this->_props)) {
            return $this->_props["name"];
        } else return null;
    }

    public function getFolders()
    {
        if (array_key_exists("folders", $this->_props)) {
            return $this->_props["folders"];
        } else return null;
    }

    public function getExtranetFolders()
    {
        if (array_key_exists("extranetFolders", $this->_props)) {
            return $this->_props["extranetFolders"];
        } else return null;
    }

    public function getLastUpdatedProfile() {
        if (array_key_exists("lastUpdatedProfile", $this->_props)) {
               
                $date = Carbon::create($this->_props["lastUpdatedProfile"])->format('d/m/Y à H:i');
                return $date ;
        } else return null;
        
    }

    public function getAssociatedFiles() {
        if (isset($this->_props["associatedFiles"]) && count($this->_props["associatedFiles"]) > 0) {
            return $this->_props["associatedFiles"];
        } else return null;
    }

    public function getCode()
    {
        if (array_key_exists("code", $this->_props)) {
            return $this->_props["code"];
        } else return null;
    }
}
