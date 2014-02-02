<?php

/**
* 
*/
namespace Models;
 
class OfficeFiltered
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

            if ($offices = $db->fetchAssoc($sqlQuery, array($city, $is_open_in_weekends, $has_support_desk)))
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
}
