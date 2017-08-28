<?

class View
{
	protected $views_dir = null;

	public function __construct($views_dir) {
		if (!is_dir($views_dir)) {
			print 'Директория с отображениями не существует!<br>';

			if (DEBUG) {
				print 'Путь: ' . $views_dir;
			}

			die();
		}

		$this->views_dir = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $views_dir);
	}

	public function render($template, $params = array()) {
		$template = $this->views_dir . '/' . $template;
		$template = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $template);

		if (!is_file($template)) {
			print 'Отображение не найдено.<br>';

			if (DEBUG) {
				print 'Файл: ' . $template;
			}

			die();
		}

		ob_start();

		extract($params);
		
		include $this->views_dir . '/layout_header.php';
		include $template;
		include $this->views_dir . '/layout_footer.php';

		return ob_get_clean();
	}

	public function addPathParams($path, $query_params = array()) {
		if (count($query_params) > 0) {
			return $path . '?' . http_build_query($query_params);
		} else {
			return $path;
		}
	}
}

