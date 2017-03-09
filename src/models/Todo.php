<?php

namespace models;
	/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 3/8/17
 * Time: 11:18 PM
 *
 * This is the entity for TODOs Items in order to handle all the tasks (todos)
 *
 */

/** @Entity */
class Todo implements \JsonSerializable
{
	/** @Id @Column(type="integer") @GeneratedValue */
	private $id;
	/** @Column(type="string",nullable=true) */
	private $description;
	/** @Column(type="integer") */
	private $position;


	/**
	 * getter for description
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * setter for description
	 * @param mixed $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 *
	 * getter for ID
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param mixed $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}


	/**
	 * Very helpful when we have to turn an object to a json response yay!!!!!
	 *
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	function jsonSerialize()
	{
		return [
			'id'	=>	$this->getId(),
			'description' => $this->getDescription(),
			'position' => $this->getPosition()
		];
	}



	static function sort($em){


	}
}