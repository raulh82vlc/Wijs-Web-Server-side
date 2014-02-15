<?php

/**
* 
*/
namespace Models;
 
class OfficeFiltered extends Office
{

    function __construct($city = false, $is_open_in_weekends = false, $has_support_desk = false, $db = false)
    {

        $this->_city = $city;
        $this->_is_open_in_weekends = $is_open_in_weekends;
        $this->_has_support_desk = $has_support_desk;

        $sqlQuery = "SELECT street, city, latitude, longitude, is_open_in_weekends, has_support_desk
            FROM offices
            WHERE city = ? AND is_open_in_weekends = ? AND has_support_desk = ?";

            if ($office = $db->fetchAssoc($sqlQuery, array($city, $is_open_in_weekends, $has_support_desk)))
            {
                 $this->_street = $office['street'];
                 $this->_latitude = $office['latitude'];
                 $this->_longitude = $office['longitude'];

                $result = array(
                'timestamp' => time(),
                'random' => rand(),
                'city' => $city,
                'street' => $this->_street
                );
                echo json_encode($result);
            }
            else
            {
                $this->_id = null;
            }
    }

    public function getOffice()
    {
        return array(
            'street' => $this->getStreet(),
            'city' => $this->getCity(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'is_open_in_weekends' => $this->getIs_open_in_weekends(),
            'has_support_desk' => $this->getHas_support_desk()
            );
    }
}
