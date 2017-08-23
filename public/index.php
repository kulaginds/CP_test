<?

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$config = [
	'settings' => [
		'displayErrorDetails' => true,
		'db' => [
			'host'   => 'localhost',
			'user'   => 'root',
			'pass'   => '123456',
			'dbname' => 'cp_test',
		],
	],
];

$app       = new \Slim\App($config);
$container = $app->getContainer();

$container['db'] = function($c) {
	$db  = $c['settings']['db'];
	$pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $db['host'], $db['dbname']), $db['user'], $db['pass']);

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $pdo;
};

$container['view'] = function ($c) {
	$view = new \Slim\Views\Twig('templates/');
	
	$basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

	return $view;
};

$app->get('/', function (Request $request, Response $response) {
	return $this->view->render($response, 'page/index.html');
})->setName('contact_list');

$app->get('/create/', function (Request $request, Response $response) {
	return $this->view->render($response, 'page/create.html');
})->setName('add_contact');

$app->run();
