<?

use \DavidePastore\Slim\Validation\Validation as Validation;

$app->get('/', HomeController::class . ':get_index_action')
	->setName('contact_list');

$app->get('/create/', HomeController::class . ':get_create_action')
	->setName('add_contact');
$app->post('/create/', HomeController::class . ':post_create_action')
	->add(new Validation($create_validators));

$app->get("/photo/{id:.+}", HomeController::class . ':get_photo_action')
	->setName('get_photo');
