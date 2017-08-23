<?

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController extends AbstractController
{
	public function get_index_action(Request $request, Response $response) {
		return $this->view->render($response, 'page/index.twig');
	}

	public function get_create_action(Request $request, Response $response) {
		return $this->view->render($response, 'page/create.twig');
	}

	public function post_create_action(Request $request, Response $response) {
		return $this->view->render($response, 'page/create.twig');
	}
}

