<?php

class Game
{
	public $id;
	/**
	 * @var $players = new Player
	 */
	public $players = array();
	private $_db;


	/**
	 * ход
	 */
	public function event()
	{
		$count = count($this->players);
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
		$res = $this->_db->select('game_players', 'gameId = ' . $this->id);
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