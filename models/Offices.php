<?php

/**
* 
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
                    "SELECT id, street, city, latitude, longitude, is_open_in_weekends, has_support_desk 
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
            else
            {
                $this->_offices = array(
                1 => array(
                    'id' => '1',
                    'street' => 'Fake',
                    'city'  => 'Fakeland'
                    ),
                2 => array(
                    'id' => '2',
                    'street' => 'Fake2',
                    'city'  => 'Fakeland2'
                    ),
                );
            }
    }

    public function getOffices() {
        return $this->_offices;
    }
}