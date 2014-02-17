<?php

/**
* Class Offices
* this class provides a list of all offices for simply list funcionality
*
* @Author Raul Hernandez Lopez - 2014
*/
namespace Models;
 
class Offices 
{
    private $_offices;

    function __construct($db = false)
    {       
            if($db)
            {
                $sqlQuery = 
                    "SELECT street, city, latitude, longitude, is_open_in_weekends, has_support_desk 
                    FROM offices";
                if ($offices = $db->fetchAll($sqlQuery))
                {
                    $this->_offices = $offices;
                }
                else
                {
                    $this->_offices = null;
                }
            }
    }

    public function getOffices() {
        return $this->_offices;
    }
}