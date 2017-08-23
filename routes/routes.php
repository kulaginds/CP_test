<?

$app->get('/', '\HomeController:get_index_action')->setName('contact_list');

$app->get('/create/', '\HomeController:get_create_action')->setName('add_contact');
$app->post('/create/', '\HomeController:post_create_action');

