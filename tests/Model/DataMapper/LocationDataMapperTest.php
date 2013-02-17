<?php

use Model\DataMapper\LocationDataMapper;
use Model\Entity\Location;

class LocationDataMapperTest extends \TestCase
{

    private $con;

    public function setUp()
    {
        $this->con = \Doctrine\DBAL\DriverManager::getConnection(array('pdo' => new \PDO('sqlite:memory:')));
        $this->con->exec(<<<SQL
DROP TABLE IF EXISTS locations;
CREATE TABLE IF NOT EXISTS locations (
    id          INTEGER      NOT NULL PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    adress      VARCHAR(255) NOT NULL,
    zip_code    INT(5)       NOT NULL,
    city        VARCHAR(255) NOT NULL,
    phone       VARCHAR(10)  NULL,
    description TEXT NULL
);
SQL
        );
    }

    public function testPersist()
    {
        $cur = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);
  
        $location = new Location();
        $location->setName('B.Box Club');
        $location->setAdress('29 rue de Eminee La Pardieu');
        $location->setZipCode('63000');
        $location->setCity('Clermont-Ferrand');
  
        (new LocationDataMapper($this->con))->persist($location);

        $cur = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $cur[0]);
    }

    public function testUpdate()
    {
        $mapper = new LocationDataMapper($this->con);

        $location = new Location();
        $location->setName('B.Box Club');
        $location->setAdress('29 rue de Eminee La Pardieu');
        $location->setZipCode('63000');
        $location->setCity('Clermont-Ferrand');

        $mapper->persist($location);

        $location->setName('Middle Night');
        $mapper->persist($location);

        $query = 'SELECT name FROM locations WHERE id = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $location->getId());
        $stmt->execute();
        $datas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertEquals(1, count($datas));
        $this->assertEquals('Middle Night', $datas[0]['name']);       
    }

    public function testRemove()
    {
        $mapper = new LocationDataMapper($this->con);

        $location = new Location();
        $location->setName('B.Box Club');
        $location->setAdress('29 rue de Eminee La Pardieu');
        $location->setZipCode('63000');
        $location->setCity('Clermont-Ferrand');

        $cur = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);

        $mapper->persist($location);

        $cur = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $cur[0]);

        $mapper->remove($location);

        $cur = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);
    }

}
