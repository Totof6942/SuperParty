<?php

namespace Model\Entity;

class Comment
{
	private $id;

	private $username;

	private $body;

	private $createdAt;

	public function _construct($username, $body, \DateTime $createdAt)
	{
		$this->setUsername($username);
		$this->setBody($body);
		$this->setCreatedAt($createdAt);
	}

	public function getId()
	{
		return $this->id;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setBody($body)
	{
		$this->body = $body;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->createdAt = $createdAt;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}
}