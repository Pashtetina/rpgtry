<?php

class Unit
{

	public $hp,
		$id,
		$maxHp,
		$baseDamage = 10,
		$baseDefense = 5,
		$name,
		$items = array();


	public function isDead()
	{
		return $this->hp <= 0;
	}


	public function receiveItem(Item $item)
	{
		DB::Instance()->insert('players_items', ['itemId' => $item->id, 'gameId' => 1, 'playerId' => $this->id]);
	}


	public function potentialDamage()
	{
		$attack = $this->baseDamage;

		foreach ($this->items as $item)
		{
			$attack = $attack+$item->attack;
		}

		return $attack;
	}


	/**
	 * @param Player $player
	 * @return int
	 */
	public function attack(Player $player)
	{
		$totalDmg = $this->potentialDamage() - $player->potentialDefense();

		if($totalDmg < 0) $totalDmg = 0;

		$ret = $player->takeDamage($player->hp - $totalDmg);

		Log::Combat($this, $player, $totalDmg);

		return $ret;
	}


	public function potentialDefense()
	{
		$defense = $this->baseDefense;

		foreach ($this->items as $item)
		{
			$defense = $defense+$item->defense;
		}

		return $defense;
	}


	public function takeDamage($amount)
	{
		$this->hp = $amount;
		if ($this->hp < 0) $this->hp = 0;

		DB::Instance()->update('game_players', ['hp' => $this->hp], '`id` = '.$this->id);
		return $this->hp;
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