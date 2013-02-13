<?php

namespace Model\Entity;

class Party
{

    /**
     * @var iny
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $message;

    /**
     * @var boolean
     */
    private $is_valid;

    /**
     * @var Location
     */
    private $location;

    /**
     * @param string   $name
     * @param DateTime $date
     * @param boolean  $is_valid
     * @param string   $message
     */
    public function __construct($name, \DateTime $date, $is_valid, $message=null)
    {
        $this->name     = $name;
        $this->date     = $date;
        $this->is_valid = $is_valid;
        $this->message  = $message;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param boolean $is_valid
     */
    public function setIsValid($is_valid)
    {
        $this->is_valid = $is_valid;
    }

    /**
     * @return boolean
     */
    public function getIsValid()
    {
        return $this->is_valid;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

}