<?php

use Model\Entity\Comment;
use Model\Entity\Location;
use Model\Finder\CommentFinder;
use Model\Finder\Hydratation;

class CommentFinderTest extends \TestCase
{

    private $con;

    public function setUp()
    {
        $this->con = \Doctrine\DBAL\DriverManager::getConnection(array('pdo' => new \PDO('sqlite:memory:')));

        $this->con->exec(<<<SQL
DROP TABLE IF EXISTS comments;
CREATE TABLE IF NOT EXISTS comments (
  id          INTEGER      NOT NULL PRIMARY KEY,
  location_id INTEGER      NOT NULL,
  username    VARCHAR(255) NOT NULL,
  body        TEXT         NOT NULL,
  created_at  DATETIME     NULL,
  message     TEXT         NULL,
  CONSTRAINT fk_location_comment
    FOREIGN KEY (location_id)
    REFERENCES locations (id)
    ON DELETE CASCADE
);

INSERT INTO comments (id, location_id, username, body, created_at) VALUES
(1, 1, 'Totof', 'Amazing', '2013-02-07 11:43:16');
INSERT INTO comments (id, location_id, username, body, created_at) VALUES
(2, 1, 'Claudus', 'Super', '2013-03-17 12:41:16');
INSERT INTO comments (id, location_id, username, body, created_at) VALUES
(3, 2, 'Michel', 'Top', '2013-04-28 20:14:43');
SQL
        );
    }

    public function testFindAll()
    {
        $commets = (new CommentFinder($this->con))->findAll();
        $this->assertEquals(3, count($commets));
    }

    public function testFindAllForLocation()
    {
        $finder = new CommentFinder($this->con);
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
        $finder = new CommentFinder($this->con);

        $comment = $finder->findOneById(1);
        $this->assertTrue($comment instanceof Comment);

        $comment = $finder->findOneById(4);
        $this->assertEquals(null, $comment);
    }

}
