<?php

namespace Model\Entity;

class Location
{
	private $id;

	private $name;

	private $adress;

	private $zipCode;

	private $city;

	private $phone;

	private $description;

	public function __construct($name, $adress, $zipCode, $city, $phone, $description)
	{
		$this->setName($name);
		$this->setAdress($adress);
		$this->setZipCode($zipCode);
		$this->setCity($city);
		$this->setPhone($phone);
		$this->setDescription($description);
	}

	public function getId() 
	{
		return $this->id;
	}

	public function setName($name) 
	{
		$this->name = $name;
	}

	public function getName() 
	{
		return $this->name;
	}

	public function setAdress($adress) 
	{
		$this->adress = $adress;
	}

	public function getAdress() 
	{
		return $this->adress;
	}

	public function setZipCode($zipCode)
	{
		$this->zipCode = $zipCode;
	}

	public function getZipCode()
	{
		return $this->zipCode;
	}

	public function setCity($city)
	{
		$this->city = $city;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	public function getPhone()
	{
		return $this->phone;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getDescription()
	{
		return $this->description;
	}
}