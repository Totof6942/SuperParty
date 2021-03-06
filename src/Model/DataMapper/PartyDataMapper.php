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
     * @param \Model\Entity\Party $object
     */
    public function persist($object)
    {
        if (null === $object->getId()) {
            $this->con->insert('parties', array(
                    'location_id' => $object->getLocation()->getId(),
                    'name'        => $object->getName(),
                    'date'        => $object->getDate()->format('Y-m-d H:i:s'),
                    'message'     => $object->getMessage(),
                ));

            (new Hydratation())->setAttributeValue($object, $this->con->lastInsertId(), 'id');
        } else {
            $this->con->update('parties', array(
                    'name'        => $object->getName(),
                    'message'     => $object->getMessage(),
                    'date'        => $object->getDate()->format('Y-m-d H:i:s'),
                ), array('id' => $object->getId()));
        }
    }

    /**
     * @param \Model\Entity\Party $object
     */
    public function remove($object)
    {
        $this->con->delete('parties', array('id' => $object->getId()));
    }

}
