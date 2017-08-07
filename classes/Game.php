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
		if ($this->_isEventable())
		{
			$event = $this->config['events'][array_rand($this->config['events'])];
			$m = 'event_' . $event;
			$this->$m();
			$this->_isEnd();
		} else var_dump('need more players');
	}


	/**
	 * берем двух различных бойцов и ударяем
	 */
	public function event_combat()
	{
		$initiator = $this->_randomPlayer();
		do
		{
			$target = $this->_randomPlayer();
		} while ($initiator == $target);

		$a = $this->players[$initiator];
		$a->attack($this->players[$target]);
	}


	/**
	 * @var Unit $finder
	 */
	public function event_loot()
	{
		$player = $this->_randomPlayer();
		$item = Item::RandomInstance();
		$player->receiveItem($item);

		$this->_db->insert('players_items', ['itemId' => $item->id, 'gameId' => 1, 'playerId' => $player->id]);
		Log::Loot($item, $player);
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


	public function __construct()
	{
		$this->_db = DB::Instance();
		$this->config = require_once('config/game.php');
		$this->prepare();
	}


	/**
	 * нужно как минимум 2 живых игрока для совершения хода
	 */
	private function _isEventable()
	{
		$i = 0;

		foreach ($this->players as $player)
			if (!$player->isDead()) $i++;

		return $i >= 2;
	}

	private function _randomPlayer()
	{
		return $this->players[mt_rand(0, count($this->players) - 1)];
	}


	/**
	 * @return $this|bool
	 */
	private function prepare()
	{
		$this->_clear();
		$res = $this->_db->select('game_players', '`gameId` = ' . $this->id . ' and `hp` > 0');
		if (!empty($res))
		{
			foreach ($res as $v)
				$this->players[] = new Player($v);

			return $this;
		} else return false;
	}

	private function _isEnd()
	{
		$res = array();
		foreach ($this->players as $player)
			if ($player->hp > 0) $res[] = $player;

		if (count($res) == 1)
			$this->_clear();
	}


	private function _clear()
	{

	}

}