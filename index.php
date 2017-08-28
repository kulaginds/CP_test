<?

// document root directory
define('DOC', __DIR__);

// application directory
define('APP', DOC . '/application');

// system directory
define('SYS', DOC . '/system');

define('BOOTSTRAP_SCRIPT', str_replace(array(DOC, '\\'), array('', '/'), __FILE__));

require_once SYS . '/handler.php';
