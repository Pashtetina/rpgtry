<?php

function my_autoloader($class) {
	include 'classes/' . $class . '.php';
}
spl_autoload_register(function ($class) {
	include 'classes/' . $class . '.php';
});

$game = new Game();
$game->find(1);
$game->players();
$game->isEventable();
