<?

$container['view'] = function ($c) {
	$view = new \Slim\Views\Twig('templates/');
	
	$basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

	return $view;
};

