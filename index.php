<?php
header('Content-Type: text/html; charset=utf-8');
function my_autoloader($class)
{
	include 'classes/' . $class . '.php';
}

spl_autoload_register(function ($class)
{
	include 'classes/' . $class . '.php';
});
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>items</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
	      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
	      integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
	        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
	        crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<?
			$game = new Game();
			$game->find(1);
			$game->players();

			?>
			<pre>
				<?var_dump($game);?>
			</pre>
			<p><a href="http://pashtet.top/items/items-list.php">список вещей</a></p>
			<form action="index.php" method="POST">
				<? for ($i = 0; $i < 4; $i++): ?>
					<div class="form-group">
						<label for="">Item <?= $i ?></label>
						<input type="text" class="form-control" name="<?= $i ?>[title]" placeholder="title">
						<input maxlength="2" type="number" class="form-control" name="<?= $i ?>[atk]" placeholder="atk">
						<input maxlength="2" type="number" class="form-control" name="<?= $i ?>[def]" placeholder="def">
					</div>
				<? endfor; ?>
				<input type="submit">
			</form>
		</div>
	</div>
</div>
</body>
</html>
<? if (!empty($_POST))
{
	foreach ($_POST as $item)
	{
		if (!empty($item['title']))
		{
			DB::Instance()->insert('items', ['title' => $item['title'], 'attack' => $item['atk'], 'defense' => $item['def']]);
		}
		unset($item);
	}
}
?>

