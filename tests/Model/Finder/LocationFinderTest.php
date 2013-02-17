<?php

use Model\Entity\Location;
use Model\Finder\LocationFinder;

class LocationFinderTest extends \TestCase
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
(1, 'B.Box Club', '29 rue de l’Eminée La Pardieu', 63000, 'Clermont-Ferrand', '0477000000', 'Ouvert depuis 2005, le B.Box Club, haut lieu de la nuit Clermontoise, est la plus grande discothèque de France.');
INSERT INTO locations (id, name, adress, zip_code, city, phone, description) VALUES
(2, 'Complexe Le Must', 'Les vorzines', 42210, 'Bellegarde-en-Forez', '0600000000', '');

SQL
        );
    }

    public function testFindAll()
    {
        $locations = (new LocationFinder($this->con))->findAll();
        $this->assertEquals(2, count($locations));
    }

    public function testFindWithParamAllForOrder()
    {
        $locations = (new LocationFinder($this->con))->findWithParamAll(array(
                'orderBy' => 'id',
                'order'   => 'DESC',
            ));

        $this->assertEquals(2, count($locations));

        $location = array_shift($locations);
        $this->assertEquals('Complexe Le Must', $location->getName());

        $location = array_shift($locations);
        $this->assertEquals('B.Box Club', $location->getName());
    }

    public function testFindWithParamAllForLimit()
    {
        $locations = (new LocationFinder($this->con))->findWithParamAll(array(
                'limit' => 1,
            ));

        $this->assertEquals(1, count($locations));
    }

    public function testFindWithParamAllForLimitAndOrder()
    {
        $locations = (new LocationFinder($this->con))->findWithParamAll(array(
                'limit'   => 1,
                'orderBy' => 'id',
                'order'   => 'ASC',
            ));

        $this->assertEquals(1, count($locations));
        $location = array_shift($locations);
        $this->assertEquals('B.Box Club', $location->getName());
    }

    public function testFindOneById()
    {
        $location = (new LocationFinder($this->con))->findOneById(1);
        $this->assertTrue($location instanceof Location);

        $location = (new LocationFinder($this->con))->findOneById(3);
        $this->assertEquals(null, $location);
    }

}