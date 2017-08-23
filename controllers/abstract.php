<?

abstract class AbstractController
{
	protected $view;
	protected $container;

	public function __construct(\Slim\Container $container) {
		$this->container = $container;
		$this->view      = $container->view;
	}
}

