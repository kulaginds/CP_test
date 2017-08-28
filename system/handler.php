<? if (!defined('APP')) exit('Hack attempt!');

define('CONTR', APP . '/controllers');
define('CONF', APP . '/configs');

require_once CONF . '/common.php';
require_once CONF . '/routes.php';

$path       = array_key_exists('PATH_INFO', $_SERVER) ? $_SERVER['PATH_INFO'] : '/';
$controller = null;
$params     = array();

foreach ($route_rules as $pattern => $controller_name) {
	$pattern = str_replace('/', '\/', $pattern);
	$match   = preg_match('/^' . $pattern . '$/', $path, $matches);

	if (false === $match) {
		print 'Ошибка в роуте.<br>';

		if (DEBUG) {
			print 'Регулярка роута: ' . $pattern;
		}

		die();
	}

	if ($match) {
		$controller = $controller_name;

		if (count($matches) > 1) {
			$params = array_splice($matches, 1);
		}
		break;
	}
}

if (empty($controller)) {
	print '404 - Страница не найдена';
	die();
}

$controller_file = CONTR . '/' . $controller . '.php';

if (!is_file($controller_file)) {
	print 'Нет контроллера по сопоставленному роуту.<br>';

	if (DEBUG) {
		print 'Регулярка роута: ' . $pattern . '<br>';
		print 'Контроллер: ' . $controller;
	}

	die();
}

require_once $controller_file;

$controller = new $controller($params);
$controller->run();
