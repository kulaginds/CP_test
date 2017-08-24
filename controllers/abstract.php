<?

abstract class AbstractController
{
	protected $view;
	protected $container;
	protected $table;

	public function __construct(\Slim\Container $container, \Slim\Views\Twig $view, Illuminate\Database\Query\Builder $table) {
		$this->view      = $view;
		$this->table     = $table;
		$this->container = $container;
	}
}

