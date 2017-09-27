<?php


class Log
{
	public static function Combat(Player $initiator, Player $target, $damage)
	{
		echo "<p>";
		echo "$initiator->name наносит $damage урона $target->name. ($target->hp / $target->maxHp)";
		echo "</p>";
	}


	public static function Loot(Item $item, Player $player)
	{
		echo "<p>";
		echo "$player->name находит шмотку! Еб твою мать, это же <em>$item->name</em>. Теперь врагам точно пиздец.";
		echo "</p>";
	}

	public static function StoreLog($gameId, $logText)
	{

	}
}