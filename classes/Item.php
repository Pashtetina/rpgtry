<?php


class Item
{
	public $attack,
		$defense,
		$id,
		$name;

	public function __construct($item)
	{
		$this->attack = $item->attack;
		$this->defense = $item->defense;
		$this->name = $item->title;
		$this->id = $item->id;
	}


	public static function RandomInstance()
	{
		$res = DB::Instance()->select('items');

		if (!empty($res))
		{
			$item = $res[mt_rand(0, count($res) - 1)];
			if (!empty($item))
			{
				return new self($item);
			} else return false;
		}
		else return false;

	}
}