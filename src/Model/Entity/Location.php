<?php

namespace Model\Entity;

class Location
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $adress;

    /**
     * @var int
     */
    private $zip_code;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $description;

    /**
     * @param int    $name
     * @param srting $adress
     * @param int    $zip_code
     * @param srting $city
     * @param srting $phone
     * @param srting $description
     */
    public function __construct($name, $adress, $zip_code, $city, $phone=null, $description=null)
    {
        $this->setName($name);
        $this->setAdress($adress);
        $this->setzip_code($zip_code);
        $this->setCity($city);
        $this->setPhone($phone);
        $this->setDescription($description);
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
     * @param string $adress
     */
    public function setAdress($adress) 
    {
        $this->adress = $adress;
    }

    /**
     * @return string
     */
    public function getAdress() 
    {
        return $this->adress;
    }

    /**
     * @param string $zip_code
     */
    public function setZipCode($zip_code)
    {
        $this->zip_code = $zip_code;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}