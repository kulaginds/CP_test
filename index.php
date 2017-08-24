<?

require 'vendor/autoload.php';

require_once __DIR__ . '/configs/loader.php';

$app = new \Slim\App($config);

require_once __DIR__ . '/initializators/loader.php';
require_once __DIR__ . '/controllers/loader.php';
require_once __DIR__ . '/validators/loader.php';
require_once __DIR__ . '/routes/routes.php';

$app->run();
