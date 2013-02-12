<?php

namespace Model\DataMapper;

use Doctrine\DBAL\Connection;

use Model\Finder\Hydratation;

class PartyDataMapper
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
     */
    public function persist($object)
    {
        if (null === $object->getId()) {
            $this->con->insert('parties', array(
/*                    'name'        => $object->getName(),
                    'adress'      => $object->getAdress(),
                    'zip_code'    => $object->getZipCode(),
                    'city'        => $object->getCity(),
                    'phone'       => $object->getPhone(),
                    'description' => $object->getDescription(),
*/                ));

            (new Hydratation())->setAttributeValue($object, $this->con->lastInsertId(), 'id');
        } else {
            $this->con->update('parties', array(
                    /*'name'        => $object->getName(),
                    'adress'      => $object->getAdress(),
                    'zip_code'    => $object->getZipCode(),
                    'city'        => $object->getCity(),
                    'phone'       => $object->getPhone(),
                    'description' => $object->getDescription(),*/

                ), array('id' => $object->getId()));
        }
    }

    /**
     * @param Location $object
     */
    public function remove($object)
    {
        $this->con->delete('parties', array('id' => $object->getId()));
    }

}
