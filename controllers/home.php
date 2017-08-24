<?

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use Slim\Http\UploadedFile as UploadedFile;

class HomeController extends AbstractController
{
	public function get_index_action(Request $request, Response $response) {
		$sort = $request->getQueryParam('sort');
		$page = ($request->getQueryParam('page', 0) > 0) ? $request->getQueryParam('page') : 1;

		// pagination
		$limit      = $this->container->get('settings')['pagination']['per_page'];
		$skip       = ($page - 1) * $limit;
		$count      = $this->table->getCountForPagination(); // Count of all available posts
		$pagination = [
			'needed'        => $count > $limit,
            'count'         => $count,
            'page'          => $page,
            'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
            'limit'         => $limit,
		];

		switch ($sort) {
			case 'first_name_asc':
				$field     = 'first_name';
				$direction = 'asc';
				break;
			case 'first_name_desc':
				$field     = 'first_name';
				$direction = 'desc';
				break;
			case 'last_name_asc':
				$field     = 'last_name';
				$direction = 'asc';
				break;
			case 'last_name_desc':
				$field     = 'last_name';
				$direction = 'desc';
				break;
			case 'id_desc':
				$field     = 'id';
				$direction = 'desc';
				break;
			default:
				$sort      = 'id_asc';
				$field     = 'id';
				$direction = 'asc';
				break;
		}

		$contacts = $this->table->orderBy($field, $direction)->skip($skip)->limit($limit)->get();

		return $this->view->render($response, 'page/index.twig', [
			'contacts' => $contacts,
			'upload_directory' => $this->container->get('upload_directory'),
			'sort' => $sort,
			'pagination' => $pagination,
		]);
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

