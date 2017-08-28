<? if (!defined('APP')) exit('Hack attempt!');

require_once SYS . '/views/view.php';
require_once CONF . '/routes.php';
require_once CONF . '/upload.php';

abstract class Controller
{
	protected $params         = null;
	protected $action         = 'index';
	protected $request_method = null;
	protected $view           = null;

	public function __construct($params = array()) {
		if (array_key_exists(0, $params)) {
			$this->action = $params[0];
			$params       = array_splice($params, 1);
		}

		$this->params         = $params;
		$this->request_method = $_SERVER['REQUEST_METHOD'];
		$this->view           = new View(APP . '/views');
	}

	public function run() {
		$action_method = 'action_' . $this->action;
		
		if (!method_exists($this, $action_method)) {
			print 'Не реализован метод запуска в контроллере.<br>';

			if (DEBUG) {
				print 'Контроллер: ' . get_class($this) . '<br>';
				print 'Действие: ' . $this->action . '<br>';
				print 'Параметры:<br>';
				var_dump($this->params);
			}

			die();
		}

		print $this->$action_method();
	}

	protected function getQueryParam($name, $default = null) {
		return array_key_exists($name, $_GET) ? htmlspecialchars($_GET[$name]) : $default;
	}

	protected function getParsedBodyParam($name, $default = null) {
		return array_key_exists($name, $_POST) ? htmlspecialchars($_POST[$name]) : $default;
	}

	protected function uploadFile($name) {
		global $max_upload_size;

		if (!array_key_exists($name, $_FILES) || $_FILES[$name]['error'] == UPLOAD_ERR_NO_FILE) {
			return false;
		}

		$uploadedFile = $_FILES[$name];

		if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
			return array('error' => 'Произошла ошибка при загрузке файла.' . ( DEBUG ? ('<br>Код ошибки: ' . $uploadedFile['error']) : '' ));
		}

		if (!$this->hasAllowedExtension($uploadedFile)) {
			return array('error' => 'Недопустимый формат.');
		}

		if ($uploadedFile['error'] == UPLOAD_ERR_FORM_SIZE || $uploadedFile['size'] > $max_upload_size) {
			return array('error' => 'Размер файла превышает максимально допустимый размер.');
		}

		return $this->moveUploadedFile($uploadedFile);
	}

	protected function hasAllowedExtension($file) {
		global $allowed_extensions;

		return in_array($file['type'], $allowed_extensions);
	}

	protected function moveUploadedFile($file) {
		global $upload_directory;

		$directory = DOC . str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $upload_directory);

		if (false === is_dir($directory)) {
			mkdir($directory);
		}

		$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
		$basename  = substr(md5(rand(1, 999999999999)), 0, 16);
		$filename  = $basename . '.' . $extension;
		$move_dir  = $directory . DIRECTORY_SEPARATOR . $filename;

		if (move_uploaded_file($file['tmp_name'], $move_dir)) {
			return $filename;
		} else {
			return false;
		}
	}
}

