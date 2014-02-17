<?php

/**
* Class OfficesFiltered
* this class provides an algorithm within the second SQL statement which calculates the distance
* from its corresponding location (city) by means of its latitude and longitude.
* once this distance has been computed, the SQL statement drives the output by distances minor than 20km
* this provides an accurate way of extracting those close offices as well as when the user chooses
* if is opened in weekends or has support desk. Limit until 50 occurences for the output of the SQL query.
* Note: the value 6371 is used because we are calculating in km, for miles is another amount.
*
* @Author Raul Hernandez Lopez - 2014
*/

namespace Models;

class OfficesFiltered
{

    private $_officesFiltered;
    private $_latitude;
    private $_longitude;

    function __construct($city = false, $is_open_in_weekends = false, $has_support_desk = false, $db = false)
    {

        $sqlCoordinates = "SELECT latitude, longitude
                            FROM offices
                            WHERE city = ?
                            ORDER BY city, id
                            LIMIT 1";

        $sqlLocation = "SELECT city, street, (6371 * (acos(cos(radians(?)) * cos(radians(latitude)) *cos(radians(longitude)-radians(?)) + sin(radians(?))*sin(radians(latitude))))) AS distance
                        FROM offices
                        WHERE is_open_in_weekends = ? AND has_support_desk = ?
                        HAVING distance < 20
                        ORDER BY city, id, distance
                        LIMIT 0 , 50";

            if ($coordinates = $db->fetchAssoc($sqlCoordinates, array($city)))
            {
                 $this->_latitude = $coordinates['latitude'];
                 $this->_longitude = $coordinates['longitude'];
            }
            else
            {
                 $this->_latitude = null;
                 $this->_longitude = null;
            }

            if ($officesFiltered = $db->fetchAll($sqlLocation, array($this->_latitude, $this->_longitude, $this->_latitude, $is_open_in_weekends, $has_support_desk)))
            {
                $this->_officesFiltered = $officesFiltered;

                $result = array(
                'timestamp' => time(),
                'random' => rand(),
                'offices' => $this->getOfficesFiltered()
                );
                echo json_encode($result);
            }
            else
            {
                $this->_officesFiltered = null;
            }            
    }

    public function getOfficesFiltered()
    {
        return $this->_officesFiltered;
    }
}
