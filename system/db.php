<? if (!defined('APP')) exit('Hack attempt!');

require_once CONF . '/db.php';

$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword, array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
));

