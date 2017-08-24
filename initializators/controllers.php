<?

$container[HomeController::class] = function ($c) {
	$view   = $c->get('view');
	$table  = $c->get('db')->table('contact');

	return new HomeController($c, $view, $table);
};

