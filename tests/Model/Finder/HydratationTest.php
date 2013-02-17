<?php

use Model\Entity\Location;
use Model\Entity\Party;
use Model\Finder\Hydratation;

class HydratationTest extends \TestCase
{

    public function testSetAttributeValue()
    {
        $location = new Location();
        (new Hydratation())->setAttributeValue($location, 1, 'id');
        $this->assertEquals(1, $location->getId());
    }

    public function testSetObject()
    {
        $values = array(
            'id'       => 1,
            'name'     => 'B.Box Club',
            'adress'   => '29 rue de Eminee La Pardieu',
            'zip_code' => 63000,
            'city'     => 'Clermont-Ferrand',
        );
        $location = new Location();
        (new Hydratation())->setObject($location, $values);

        $this->assertEquals(1, $location->getId());
        $this->assertEquals('B.Box Club', $location->getName());
        $this->assertEquals('29 rue de Eminee La Pardieu', $location->getAdress());
        $this->assertEquals(63000, $location->getZipCode());
        $this->assertEquals('Clermont-Ferrand', $location->getCity());
    }

    public function testSetObjectWithDate()
    {
        $values = array(
            'id'   => 1,
            'name' => 'Soiree infirmiere',
            'date' => '2013-03-15 22:30:00',
        );
        $party = new Party();
        (new Hydratation())->setObject($party, $values);

        $this->assertEquals(1, $party->getId());
        $this->assertEquals('Soiree infirmiere', $party->getName());
        $this->assertEquals(new \DateTime('2013-03-15 22:30:00'), $party->getDate());
    }

    public function testSetObjectWithDateTime()
    {
         $values = array(
            'id'   => 1,
            'name' => 'Soiree infirmiere',
            'date' => new \DateTime('2013-03-15 22:30:00'),
        );
        $party = new Party();
        (new Hydratation())->setObject($party, $values);

        $this->assertEquals(1, $party->getId());
        $this->assertEquals('Soiree infirmiere', $party->getName());
        $this->assertEquals(new \DateTime('2013-03-15 22:30:00'), $party->getDate());
    }

}
