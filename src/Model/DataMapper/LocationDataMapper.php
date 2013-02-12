<?php

namespace Model\DataMapper;

use Doctrine\DBAL\Connection;

class LocationDataMapper
{
    
    /**
     * @var ressource
     */
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    /**
     * @param  Location $object
     * @return int
     */
    public function persist($object)
    {

    }

    /**
     * @param Location $object
     */
    public function remove($object)
    {
        $this->con->delete('locations', array('id' => $object->getId()));
    }

}
