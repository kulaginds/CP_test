<?

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use Slim\Http\UploadedFile as UploadedFile;

class HomeController extends AbstractController
{
	public function get_index_action(Request $request, Response $response) {
		return $this->view->render($response, 'page/index.twig');
	}

	public function get_create_action(Request $request, Response $response) {
		return $this->view->render($response, 'page/create.twig');
	}

	public function post_create_action(Request $request, Response $response) {
		$params = [
			'errors'  => [],
			'success' => false,
		];

		$inputs = [
			'first_name' => $request->getParsedBodyParam('first_name', ''),
			'last_name'  => $request->getParsedBodyParam('last_name', ''),
			'email'      => $request->getParsedBodyParam('email', ''),
			'phone'      => $request->getParsedBodyParam('phone', ''),
			'comment'    => $request->getParsedBodyParam('comment', ''),
		];

		$uploadedFiles = $request->getUploadedFiles();
		$uploadedFile  = $uploadedFiles['photo'];

		if ($request->getAttribute('has_errors')) {
			$params['errors'] = $request->getAttribute('errors');
		} else {
			if ($uploadedFile->getError() !== UPLOAD_ERR_NO_FILE) {
				if ($uploadedFile->getError() === UPLOAD_ERR_OK && $this->hasAllowedExtension($uploadedFile)) {
					$inputs['photo'] = $this->moveUploadedFile($uploadedFile);
				}
			}

			if (empty($inputs['comment'])) {
				unset($inputs['comment']);
			}

			try {
				$this->table->insert($inputs);
				$params['success'] = true;
				$inputs = [];
			} catch (Illuminate\Database\QueryException $e) {
				if (false !== strpos($e->getMessage(), '1062 Duplicate entry')) {
					$params['error'] = 'Контакт с введенными e-mail и телефоном уже сущетвует.';
				}
			}
		}

		$params = array_merge($params, $inputs);

		return $this->view->render($response, 'page/create.twig', $params);
	}

	protected function hasAllowedExtension($file) {
		$allowed   = $this->container->get('allowed_extensions');
		$extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);

		return in_array($extension, $allowed);
	}

	protected function moveUploadedFile(UploadedFile $uploadedFile)
	{
		$directory = $this->container->get('upload_directory');
		$extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
		$basename  = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
		$filename  = sprintf('%s.%0.8s', $basename, $extension);

		$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

		return $filename;
	}
}

