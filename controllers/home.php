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
		$uploadedFiles = $request->getUploadedFiles();
		$uploadedFile  = $uploadedFiles['photo'];

		if ($uploadedFile->getError() === UPLOAD_ERR_OK && $this->hasAllowedExtension($uploadedFile)) {
			$filename = $this->moveUploadedFile($uploadedFile);
		}

		return $this->view->render($response, 'page/create.twig');
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

