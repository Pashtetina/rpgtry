<?php

class Unit
{

	public $hp,
		$maxHp;


	public function isDead()
	{
		return $this->hp <= 0;
	}
}