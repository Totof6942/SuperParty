<?php

namespace Model\Finder;

use Doctrine\DBAL\Connection;

use Model\Entity\Location;
use Model\Entity\Comment;
use Model\Finder\CommentFinder;

class CommentFinder implements FinderInterface
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
                ->select('c.*')
                ->from('comments', 'c');

        $sth = $this->con->executeQuery($qb);
        $datas = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $comments = array();

        foreach ($datas as $cur) {
            $comments[$cur['id']] = $this->hydrate($cur);
        }

        return $comments;
    }

    /**
     * Returns all comments for a Location
     *
     * @param Location $location
     * 
     * @return array
     */
    public function findAllForLocation(Location $location)
    {
        $qb = $this->con->createQueryBuilder()
                ->select('c.*')
                ->from('comments', 'c')
                ->where('c.location_id = :location_id');

        $sth = $this->con->executeQuery($qb, array(':location_id' => $location->getId()));
        $datas = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $comments = array();

        foreach ($datas as $cur) {
            $comments[$cur['id']] = $this->hydrate($cur);
        }

        return $comments;
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
                ->select('c.*')
                ->from('comments', 'c')
                ->where('c.id = :id');

        $cur = $this->con->fetchAssoc($qb, array('id' => $id));

        if (!empty($cur)) {
            return $this->hydrate($cur);
        }

        return null;
    }

    /**
     * Create a Comment
     *
     * @param $cur array
     *
     * @return Comment
     */
    private function hydrate($cur)
    {
        $cur['created_at'] = (null === $cur['created_at']) ? null : new \DateTime($cur['created_at']);
        $comment = new Comment();
        (new Hydratation())->setObject($comment, $cur);

        return $comment;
    }

}