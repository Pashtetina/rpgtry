<?php


class Log
{
	public static function Combat(Player $initiator, Player $target, $damage)
	{
		echo "<p>";
		echo "$initiator->name наносит $damage урона $target->name. ($target->hp / $target->maxHp)";
		echo "</p>";
	}
}