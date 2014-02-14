<?php

/**
* 
*/
namespace Models;
 
class Office 
{
    private $_id;
    private $_street;
    private $_city;
    private $_latitude;
    private $_longitude;
    private $_is_open_in_weekends;
    private $_has_support_desk;

    function __construct($id=1, $db = false)
    {
        $this->_id = $id;
        $sqlQuery = "SELECT id, street, city, latitude, longitude, is_open_in_weekends, has_support_desk
            FROM offices
            WHERE id = ?";

            if ($office = $db->fetchAssoc($sqlQuery, array($id)))
            {
                 $this->_street = $office['street'];
                 $this->_city = $office['city'];
                 $this->_latitude = $office['latitude'];
                 $this->_longitude = $office['longitude'];
                 $this->_is_open_in_weekends = $office['is_open_in_weekends'];
                 $this->_has_support_desk = $office['has_support_desk'];
            }
            else
            {
                $this->_id = null;
            }
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getStreet()
    {
        return $this->_street;
    }

    public function getCity()
    {
        return $this->_city;
    }

    public function getLatitude()
    {
        return $this->_latitude;
    }

    public function getLongitude()
    {
        return $this->_longitude;
    }

    public function getIs_open_in_weekends()
    {
        return $this->_is_open_in_weekends;
    }

    public function getHas_support_desk()
    {
        return $this->_has_support_desk;
    }

    public function getOffice()
    {
        return array(
            'id' => $this->getId(),
            'street' => $this->getStreet(),
            'city' => $this->getCity(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'is_open_in_weekends' => $this->getIs_open_in_weekends(),
            'has_support_desk' => $this->getHas_support_desk()
            );
    }
}
