<?php

namespace Model\Finder;

use Doctrine\DBAL\Connection;

use Model\Entity\Location;
use Model\Finder\LocationFinder;

class LocationFinder implements FinderInterface
{

    /**
     * @var ressource
     */
    private $con;

    /**
     * Constructor
     *
     * @param ressource $con Instance of Connection
     */
    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    /**
     * Returns all elements.
     *
     * @return array
     */
    public function findAll()
    {
        $qb = $this->con->createQueryBuilder()
                ->select('l.*')
                ->from('locations', 'l');


        $sth = $this->con->executeQuery($qb);
        $datas = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $locations = array();

        foreach ($datas as $cur) {
            $locations[$cur['id']] = $this->hydrate($cur);
        }

        return $locations;
    }

    /**
     * Returns all elements with param
     *
     * @param array $param (example limit => 5)
     *
     * @return array
     */
    public function findWithParamAll(array $param)
    {
        $qb = $this->con->createQueryBuilder()
                ->select('l.*')
                ->from('locations', 'l');

        if (array_key_exists('limit', $param)) {
            if (is_numeric($param['limit'])) {
                $qb->setMaxResults($param['limit']);
            }
        }

        if (array_key_exists('orderBy', $param)) {
            $order = (array_key_exists('order', $param) && 'DESC' === strtoupper($param['order'])) ? 'DESC' : 'ASC';
            $qb->orderBy($param['orderBy'], $order);
        }

        $sth = $this->con->executeQuery($qb);
        $datas = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $locations = array();

        foreach ($datas as $cur) {
            $locations[$cur['id']] = $this->hydrate($cur);
        }

        return $locations;
    }

    /**
     * Retrieve an element by its id.
     *
     * @param  mixed      $id
     * @return null|mixed
     */
    public function findOneById($id)
    {
        $qb = $this->con->createQueryBuilder()
                ->select('l.*')
                ->from('locations', 'l')
                ->where('l.id = :id');

        $cur = $this->con->fetchAssoc($qb, array('id' => $id));

        if (!empty($cur)) {
            return $this->hydrate($cur);
        }

        return null;
    }

    /**
     * Return a Location with these comments and parties
     *
     * @param  mixed      $id
     * @return null|mixed
     */
    public function findOneByIdWithCommentsAndParties($id)
    {
        $location = $this->findOneById($id);

        if (!empty($location)) {
            $commentFinder = new CommentFinder($this->con);
            $location->setComments($commentFinder->findAllForLocation($location));
            
            $partyFinder = new PartyFinder($this->con);
            $location->setParties($partyFinder->findAllForLocation($location));

            return $location;
        }

        return null;
    }

    /**
     * Create a Location
     *
     * @param $cur array
     *
     * @return Location
     */
    private function hydrate($cur)
    {
        $location = new Location();
        (new Hydratation())->setObject($location, $cur);

        return $location;
    }

}