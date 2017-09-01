<? if (!defined('APP')) exit('Hack attempt!');

require_once SYS . '/db.php';

abstract class Model
{
	public $id      = null;
	public $errors  = array();

	private $_where = array();
	private $_data  = array();

	public function __get($name) {
		if (array_key_exists($name, $this->_data)) {
			return $this->_data[$name];
		} else {
			return null;
		}
	}

	public function __construct($data = array()) {
		$this->assign_attributes($data);
	}

	static public function table() {
		throw new Exception('Not implemented');
	}

	static public function factory($data) {
		throw new Exception('Not implemented');
	}

	static public function fields() {
		return array('id');
	}

	public function valid() {
		return true;
	}

	static public function all() {
		global $pdo;
		
		$stmt = $pdo->query('SELECT * FROM ' . static::table());
		$data = array();

		while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = static::factory($myrow);
		}

		return $data;
	}

	static public function all_order_by_limit($field, $direction, $offset = 0, $limit = 0) {
		if (!in_array($field, static::fields())) {
			print 'Ошибка модели ' . static::table() . '<br>';

			if (DEBUG) {
				print 'Функция: all_order_by<br>';
				print 'Параметр field: ' . $field . '<br>';
				print 'Параметр direction: ' . $direction . '<br>';
			}

			die();
		}

		$direction = in_array($direction, array('asc', 'desc')) ? $direction : 'asc';

		global $pdo;

		$sql = 'SELECT * FROM ' . static::table() . ' ORDER BY ' . $field . ' ' . $direction;

		if ($limit > 0) {
			$sql .= " LIMIT $offset, $limit";
		}
		
		$stmt = $pdo->query($sql);

		$data = array();

		while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = static::factory($myrow);
		}

		return $data;
	}

	static public function count_all() {
		global $pdo;
		
		$stmt = $pdo->query('SELECT COUNT(*) AS cnt FROM ' . static::table());
		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data['cnt'];
	}

	public static function one($id) {
		global $pdo;
		
		$stmt = $pdo->prepare('SELECT * FROM ' . static::table() . ' WHERE id = :id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return static::factory($stmt->fetch(PDO::FETCH_ASSOC));
	}

	public function assign_attributes($data) {
		$this->_data = $data;

		foreach (static::fields() as $name) {
			if (array_key_exists($name, $data)) {
				$this->$name = $data[$name];
			}
		}
	}

	public function save() {
		if (!$this->valid()) {
			return false;
		}

		global $pdo;
		
		$allowed = array_keys($this->_data);
		$values  = array_values($this->_data);

		if ($this->id) {
			$sql  = 'UPDATE ' . static::table() . ' SET ' . $this->pdoSet($allowed, $values) . ' WHERE id = :id';
			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':id', $this->id);
			$stmt->execute();
		} else {
			$sql  = 'INSERT INTO ' . static::table() . ' SET ' . $this->pdoSet($allowed, $values);
			$stmt = $pdo->prepare($sql);
		}

		foreach ($this->_data as $key => &$value) {
			$stmt->bindParam(':' . $key, $value);
		}

		return $stmt->execute();
	}

	public function delete() {
		global $pdo;
		
		$stmt = $pdo->prepare('DELETE FROM ' . static::table() . ' WHERE id = :id');
		$stmt->bindParam(':id', $this->id);

		return $stmt->execute();
	}

	protected function pdoSet($allowed) {
		$set    = '';

		foreach ($allowed as $field) {
			$set .= "`".str_replace("`", "``", $field)."`". "=:$field, ";
		}

		return substr($set, 0, -2); 
	}

	const VALIDMSG_ALPHA = 'Поле может содержать только буквы английского и русского алфавитов.';
	protected function validAlpha($field_name, $field_value, $error_msg = null) {
		$pattern = '/^[a-zA-ZАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]+$/';

		if (!preg_match($pattern, $field_value)) {
			if (empty($error_msg)) {
				$error_msg = self::VALIDMSG_ALPHA;
			}

			$this->errors[$field_name][] = $error_msg;

			return false;
		} else {
			return true;
		}
	}

	const VALIDMSG_NOWHITESPACE = 'Поле не может содержать пробельных символов.';
	protected function validNoWhitespace($field_name, $field_value, $error_msg = null) {
		$pattern = '/\s+/';

		if (preg_match($pattern, $field_value)) {
			if (empty($error_msg)) {
				$error_msg = self::VALIDMSG_NOWHITESPACE;
			}

			$this->errors[$field_name][] = $error_msg;

			return false;
		} else {
			return true;
		}
	}

	const VALIDMSG_NOEMPTY = 'Поле не может быть пустым.';
	protected function validNoEmpty($field_name, $field_value, $error_msg = null) {
		if (empty($field_value)) {
			if (empty($error_msg)) {
				$error_msg = self::VALIDMSG_NOEMPTY;
			}

			$this->errors[$field_name][] = $error_msg;

			return false;
		} else {
			return true;
		}
	}

	const VALIDMSG_MAX_INCLUSIVE = 'Максимально допустимая длина поля %d символов включительно.';
	const VALIDMSG_MAX_EXCLUSIVE = 'Максимально допустимая длина поля %d символов исключительно.';
	protected function validMax($field_name, $field_value, $max, $max_inclusive = false, $error_msg = null) {
		if ($field_value >= $max) {
			if ($field_value == $max && $max_inclusive) {
				return true;
			}

			if (empty($error_msg)) {
				$error_msg = $max_inclusive ? self::VALIDMSG_MAX_INCLUSIVE : self::VALIDMSG_MAX_EXCLUSIVE;
			}

			$this->errors[$field_name][] = sprintf($error_msg, $max);

			return false;
		} else {
			return true;
		}
	}

	const VALIDMSG_EMAIL = 'Поле содержит некорректный e-mail адрес.';
	protected function validEmail($field_name, $field_value, $error_msg = null) {
		if (!filter_var($field_value, FILTER_VALIDATE_EMAIL)) {
			if (empty($error_msg)) {
				$error_msg = self::VALIDMSG_EMAIL;
			}

			$this->errors[$field_name][] = sprintf($error_msg, $max);

			return false;
		}

		return true;
	}

	const VALIDMSG_PHONE = 'Поле содержит некорректный номер телефона.';
	protected function validPhone($field_name, $field_value, $error_msg = null) {
		$pattern = '/^\+[0-9]{10,18}+$/';

		if (!preg_match($pattern, $field_value)) {
			if (empty($error_msg)) {
				$error_msg = self::VALIDMSG_PHONE;
			}

			$this->errors[$field_name][] = sprintf($error_msg, $max);

			return false;
		}

		return true;
	}
}

