<?php


class Item
{
	public $attack,
		$defense,
		$name;

	public function __construct($item)
	{
		$this->attack = $item->attack;
		$this->defense = $item->defense;
		$this->name = $item->title;
	}
}