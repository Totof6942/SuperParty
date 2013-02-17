<?php

use Model\DataMapper\CommentDataMapper;
use Model\Entity\Location;
use Model\Entity\Comment;
use Model\Finder\LocationFinder;

class CommentDataMapperTest extends \TestCase
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
SQL
        );
    }

    public function testPersist()
    {
        $cur = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);
        
        $location = (new LocationFinder($this->con))->findOneById(1);
        $comment = new Comment();
        $comment->setLocation($location);
        $comment->setUsername('Totof');
        $comment->setBody('Super comment');

        (new CommentDataMapper($this->con))->persist($comment);

        $cur = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $cur[0]);
    }

    public function testUpdate()
    {
        $mapper = new CommentDataMapper($this->con);

        $location = (new LocationFinder($this->con))->findOneById(1);
        $comment = new Comment();
        $comment->setLocation($location);
        $comment->setUsername('Totof');
        $comment->setBody('Super comment');

        $mapper->persist($comment);

        $comment->setUsername('Claudus');
        $mapper->persist($comment);

        $query = 'SELECT username FROM comments WHERE id = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $comment->getId());
        $stmt->execute();
        $datas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertEquals(1, count($datas));
        $this->assertEquals('Claudus', $datas[0]['username']);       
    }

    public function testRemove()
    {
        $mapper = new CommentDataMapper($this->con);

        $location = (new LocationFinder($this->con))->findOneById(1);
        $comment = new Comment();
        $comment->setLocation($location);
        $comment->setUsername('Totof');
        $comment->setBody('Super comment');

        $cur = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);

        $mapper->persist($comment);

        $cur = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $cur[0]);

        $mapper->remove($comment);

        $cur = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $cur[0]);
    }
}
