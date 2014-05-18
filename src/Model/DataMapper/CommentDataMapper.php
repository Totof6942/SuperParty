<?php

namespace Model\DataMapper;

use Doctrine\DBAL\Connection;

use Model\Finder\Hydratation;

class CommentDataMapper
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
     * @param \Model\Entity\Comment $object
     */
    public function persist($object)
    {
        if (null === $object->getId()) {
            $this->con->insert('comments', array(
                    'location_id' => $object->getLocation()->getId(),
                    'username'    => $object->getUsername(),
                    'body'        => $object->getBody(),
                    'created_at'  => $object->getCreatedAt()->format('Y-m-d H:i:s'),
                ));

            (new Hydratation())->setAttributeValue($object, $this->con->lastInsertId(), 'id');
        } else {
            $this->con->update('comments', array(
                    'username' => $object->getUsername(),
                    'body'     => $object->getBody(),
                ), array('id' => $object->getId()));
        }
    }

    /**
     * @param \Model\Entity\Comment $object
     */
    public function remove($object)
    {
        $this->con->delete('comments', array('id' => $object->getId()));
    }

}
