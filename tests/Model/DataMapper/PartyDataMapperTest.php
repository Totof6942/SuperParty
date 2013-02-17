<?php

use Model\DataMapper\PartyDataMapper;
use Model\Entity\Location;
use Model\Entity\Party;
use Model\Finder\LocationFinder;

class PartyDataMapperTest extends \TestCase
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

INSERT INTO locations (id, name, adress, zip_code, city, phone, description) VALUES
(1, 'B.Box Club', '29 rue de Eminee La Pardieu', 63000, 'Clermont-Ferrand', '0477000000', 'Ouvert depuis 2005, le B.Box Club, haut lieu de la nuit Clermontoise, est la plus grande discotheque de France.');

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
SQL
        );
    }

    public function testPersist()
    {
        $cur = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);
        
        $location = (new LocationFinder($this->con))->findOneById(1);
        $party = new Party();
        $party->setLocation($location);
        $party->setName('Soiree infirmiere');
        $party->setDate(new \DateTime('2013-03-15 22:30:00'));

        (new PartyDataMapper($this->con))->persist($party);

        $cur = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $cur[0]);
    }

    public function testUpdate()
    {
        $mapper = new PartyDataMapper($this->con);

        $location = (new LocationFinder($this->con))->findOneById(1);
        $party = new Party();
        $party->setLocation($location);
        $party->setName('Soiree infirmiere');
        $party->setDate(new \DateTime('2013-03-15 22:30:00'));

        $mapper->persist($party);

        $party->setName('Fin partiels');
        $mapper->persist($party);

        $query = 'SELECT name FROM parties WHERE id = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $party->getId());
        $stmt->execute();
        $datas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertEquals(1, count($datas));
        $this->assertEquals('Fin partiels', $datas[0]['name']);       
    }

    public function testRemove()
    {
        $mapper = new PartyDataMapper($this->con);

        $location = (new LocationFinder($this->con))->findOneById(1);
        $party = new Party();
        $party->setLocation($location);
        $party->setName('Soiree infirmiere');
        $party->setDate(new \DateTime('2013-03-15 22:30:00'));

        $cur = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);

        $mapper->persist($party);

        $cur = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $cur[0]);

        $mapper->remove($party);

        $cur = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);
    }
}
