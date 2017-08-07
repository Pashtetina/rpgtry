<?php

class Game
{
	public $id;
	/**
	 * @var $players = new Player
	 */
	public $players = array();
	public $config = array();
	private $_db;


	/**
	 * ход
	 */
	public function event()
	{
		if ($this->isEventable())
		{
			$event = $this->config['events'][array_rand($this->config['events'])];
			$m = 'event_' . $event;
			$this->$m();
		} else var_dump('need more players');
	}


	public function event_combat()
	{
		$initiator = mt_rand(0, count($this->players) - 1);
		do
		{
			$target = mt_rand(0, count($this->players) - 1);
		} while ($initiator == $target);


		$a = $this->players[$initiator];
		$a->attack($this->players[$target]);
	}


	public function event_loot()
	{
		var_dump('loot');
	}


	/**
	 * @param $chatId
	 * @return int
	 * найти игру
	 */
	public function find($chatId)
	{
		$res = $this->_db->select('games', '`chatId`=' . $chatId);

		if (!empty($res) && $res[0]->id != 0)
		{
			$this->id = $res[0]->id;
			return $this->id;
		} else return 0;
	}


	/**
	 * @return $this|bool
	 * наполнение игроками
	 */
	public function players()
	{
		$res = $this->_db->select('game_players', '`gameId` = ' . $this->id . ' and `hp` > 0');
		if (!empty($res))
		{
			foreach ($res as $v)
				$this->players[] = new Player($v);

			return $this;
		} else return false;
	}


	public function __construct()
	{
		$this->_db = DB::Instance();
		$this->config = require_once('config/game.php');
	}


	/**
	 * нужно как минимум 2 живых игрока для совершения хода
	 */
	public function isEventable()
	{
		$i = 0;

		foreach ($this->players as $player)
		{

			if (!$player->isDead()) $i++;
		}

		return $i >= 2;
	}

}