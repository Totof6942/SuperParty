<?php

namespace Model\Entity;

class Comment
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $body;

    /**
     * @var DateTime
     */
    private $created_at;

    /**
     * @param string   $username
     * @param string   $body
     * @param DateTime $created_at
     */
    public function _construct($username, $body, \DateTime $created_at=null)
    {
        $this->setUsername($username);
        $this->setBody($body);
        
        $created_at = (empty($created_at)) ? $this->created_at = new \DateTime() : $created_at;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

}