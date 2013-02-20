<?php

use Model\Entity\Party;
use Model\Entity\Location;
use Model\Finder\PartyFinder;
use Model\Finder\Hydratation;

class PartyFinderTest extends \TestCase
{

    private $con;

    public function setUp()
    {
        $this->con = \Doctrine\DBAL\DriverManager::getConnection(array('pdo' => new \PDO('sqlite:memory:')));

        $this->con->exec(<<<SQL
DROP TABLE IF EXISTS parties;
CREATE TABLE IF NOT EXISTS parties (
  id          INTEGER      NOT NULL PRIMARY KEY,
  location_id INTEGER      NOT NULL,
  name        VARCHAR(255) NOT NULL,
  date        DATETIME     NOT NULL,
  message     TEXT         NULL,
  CONSTRAINT fk_location_party
    FOREIGN KEY (location_id)
    REFERENCES locations (id)
    ON DELETE CASCADE
);

INSERT INTO parties (id, location_id, name, date) VALUES
(1, 1, 'Fin partiels', '2013-02-07 11:43:16');
INSERT INTO parties (id, location_id, name, date) VALUES
(2, 1, 'Soiree infirmieres', '2013-03-17 12:41:16');
INSERT INTO parties (id, location_id, name, date) VALUES
(3, 2, 'Soiree mousse', '2013-04-28 20:14:43');
SQL
        );
    }

    public function testFindAll()
    {
        $commets = (new PartyFinder($this->con))->findAll();
        $this->assertEquals(3, count($commets));
    }

    public function testFindAllForLocation()
    {
        $finder = new PartyFinder($this->con);
        $hydrate = new Hydratation();
        $location = new Location();

        $hydrate->setAttributeValue($location, 1, 'id');
        $commets = $finder->findAllForLocation($location);
        $this->assertEquals(2, count($commets));

        $hydrate->setAttributeValue($location, 2, 'id');
        $commets = $finder->findAllForLocation($location);
        $this->assertEquals(1, count($commets));
    }

    public function testFindOneById()
    {
        $finder = new PartyFinder($this->con);

        $party = $finder->findOneById(1);
        $this->assertTrue($party instanceof Party);

        $party = $finder->findOneById(4);
        $this->assertEquals(null, $party);
    }

}
