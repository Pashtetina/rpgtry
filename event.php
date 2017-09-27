<?php
function my_autoloader($class)
{
	include 'classes/' . $class . '.php';
}

spl_autoload_register(function ($class)
{
	include 'classes/' . $class . '.php';
});


if (isset($_POST['username']) && isset($_POST['gameId']))
{

	$exists = DB::Instance()->select('games', '`chatId` = '.$_POST['gameId']);

	if (!$exists)
	{
		$res = DB::Instance()->insert('games', ['chatId' => $_POST['gameId']]);
		if (!$res)
		{
			var_dump('op:insert::err');
		}
	}
	else
	{
		$playerExists = DB::Instance()->select('game_players', '`gameId` = ' .$_POST['gameId'].' and `username` = "' .$_POST['username'].'"');

		 if($playerExists)
		 	echo 'Ты уже в игре!';
		 else
		 {
			 $res = DB::Instance()->insert('game_players', ['gameId' => $_POST['gameId'], 'username' => $_POST['username']]);
			 if($res)
			 	echo 'Окей, ты в игре!';
		 }

	}

}
elseif(isset($_POST['gameId']) && isset($_POST['event']))
{
	$game = new Game();
	$game->find($_POST['gameId']);
	$game->event();
}
?>