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


	public static function GetRandom()
	{
		$res = DB::Instance()->select('items');

		if (!empty($res))
		{
			$rnd = mt_rand(0, count($res) - 1);
			$item = $res[$rnd];
			if (!empty($item))
			{
				return new self($item);
			}
		}
		else return false;

	}
}