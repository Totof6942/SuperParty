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
     * @var array
     */
    private $locations;

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
        $sth = $this->con->prepare("SELECT * FROM locations");
        $sth->execute();
        $datas = $sth->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($datas as $cur) {
            $this->locations[$cur['id']] = $this->hydrate($cur);
        }

        return $this->locations;
    }

    /**
     * Retrieve an element by its id.
     *
     * @param  mixed      $id
     * @return null|mixed
     */
    public function findOneById($id)
    {
        $cur = $this->con->fetchAssoc("SELECT * FROM locations WHERE id = :id", array('id' => $id));

        if (!empty($cur)) {
            return $this->hydrate($cur);
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
        $location = new Location($cur['name'], $cur['adress'], $cur['zip_code'], $cur['city'], $cur['phone'], $cur['description']);
        (new Hydratation())->setAttributeValue($location, $cur['id'], 'id');

        return $location;
    }

}