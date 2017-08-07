<?php

class Unit
{

	public $hp,
		$id,
		$maxHp,
		$baseDamage = 10,
		$name,
		$items = array();


	public function isDead()
	{
		return $this->hp <= 0;
	}


	public function doDamage()
	{

	}


	public function __construct($stats)
	{
		$this->loadStats($stats);
		$this->loadItems();
	}


	public function loadItems()
	{
		$res = DB::Instance()->select('players_items', '`gameId` = 1 and `playerId` = ' . $this->id);

		if (!empty($res))
		{

			foreach ($res as $item)
			{
				$dbitem = DB::Instance()->select('items', '`id` = ' . $item->itemId);
				if ($dbitem)
				{
					$this->items[] = new Item($dbitem[0]);
				}
			}
		}
	}


	public function loadStats($stats)
	{
		$this->id = $stats->id;
		$this->hp = $stats->hp;
		$this->maxHp = $stats->maxHp;
		$this->name = $stats->username;
	}
}