<?php

class Unit
{

	public $hp,
		$maxHp,
		$name;


	public function isDead()
	{
		return $this->hp <= 0;
	}


	public function __construct($stats)
	{
			$this->hp = $stats->hp;
			$this->maxHp = $stats->maxHp;
			$this->name = $stats->username;
	}
}