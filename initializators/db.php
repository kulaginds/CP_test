<?

$container['db'] = function($c) {
	$db  = $c['settings']['db'];
	$pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $db['host'], $db['dbname']), $db['user'], $db['pass']);

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $pdo;
};

