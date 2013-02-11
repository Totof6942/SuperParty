<?php

namespace Model\Finder;

use Doctrine\DBAL\Connection;

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

/*        foreach ($datas as $cur) {
            $this->locations[$cur['id']] = $this->hydrate($cur);
        }

        return $this->locations;*/
    }

    /**
     * Retrieve an element by its id.
     *
     * @param  mixed      $id
     * @return null|mixed
     */
    public function findOneById($id)
    {
        $sth = $this->con->prepare("SELECT * FROM locations WHERE id = :id");
        $sth->bindValue(':id', $id);
        $sth->execute();
        $cur = $sth->fetch(\PDO::FETCH_ASSOC);

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
        $date = (null === $cur['created_at']) ? null : new \DateTime($cur['created_at']);
        $location = new Location($cur['name'], $date);
        $location->setId($cur['id']);

        return $location;
    }

}