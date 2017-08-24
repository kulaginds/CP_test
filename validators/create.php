<?

use Respect\Validation\Validator as Validator;

$create_validators = [
	'first_name' => Validator::alpha($validators_config['alpha'])->noWhitespace()->notEmpty()->max(30, true),
	'last_name'  => Validator::alpha($validators_config['alpha'])->noWhitespace()->notEmpty()->max(30, true),
	'email'      => Validator::email()->notEmpty()->max(50, true),
	'phone'      => Validator::phone()->notEmpty(),
	'comment'    => Validator::stringType()->max(200, true),
];
