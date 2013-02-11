<?php

namespace Model\Entity;

class Party
{
	private $id;

	private $name;

	private $date;

	private $message;

	public function __construct($name, \DateTime $date, $message)
	{
		$this->setName($name);
		$this->setDate($date);
		$this->setMessage($message);
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

	public function setDate(\DateTime $ddate)
	{
		$this->date = $date;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function getMessage()
	{
		return $this->message;
	}
}