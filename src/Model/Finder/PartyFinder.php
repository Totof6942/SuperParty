<?php

namespace Model\Finder;

use Doctrine\DBAL\Connection;

use Model\Entity\Location;
use Model\Entity\Party;
use Model\Finder\PartyFinder;

class PartyFinder implements FinderInterface
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
                ->select('p.*')
                ->from('parties', 'p');

        $sth = $this->con->executeQuery($qb);
        $datas = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $parties = array();

        foreach ($datas as $cur) {
            $parties[$cur['id']] = $this->hydrate($cur);
        }

        return $parties;
    }

    /**
     * Returns all future parties.
     *
     * @return array
     */
    public function findAllFuture()
    {
        $qb = $this->con->createQueryBuilder()
                ->select('p.*')
                ->from('parties', 'p')
                ->where('p.date >= NOW()')
                ->orderBy('p.date', 'ASC');

        $sth = $this->con->executeQuery($qb);
        $datas = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $parties = array();

        foreach ($datas as $cur) {
            $parties[$cur['id']] = $this->hydrate($cur);
        }

        return $parties;
    }

    /**
     * Returns all parties for a Location
     *
     * @param Location $location
     * 
     * @return array
     */
    public function findAllForLocation(Location $location)
    {
        $qb = $this->con->createQueryBuilder()
                ->select('p.*')
                ->from('parties', 'p')
                ->where('p.location_id = :location_id');

        $sth = $this->con->executeQuery($qb, array(':location_id' => $location->getId()));
        $datas = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $parties = array();

        foreach ($datas as $cur) {
            $parties[$cur['id']] = $this->hydrate($cur);
        }

        return $parties;
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
                ->select('p.*')
                ->from('parties', 'p')
                ->where('p.id = :id');

        $cur = $this->con->fetchAssoc($qb, array('id' => $id));

        if (!empty($cur)) {
            return $this->hydrate($cur);
        }

        return null;
    }

    /**
     * Create a Party
     *
     * @param $cur array
     *
     * @return Party
     */
    private function hydrate($cur)
    {
        $date = (null === $cur['date']) ? null : new \DateTime($cur['date']);
        $party = new Party($cur['name'], $date, $cur['message']);
        
        (new Hydratation())->setAttributeValue($party, $cur['id'], 'id');

        return $party;
    }

}