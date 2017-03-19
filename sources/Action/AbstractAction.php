<?php
/**
 * Class AbstractAction
 */
namespace Action;
use \Model\ViewContentDecorator;
use \Moro\Platform\Action\AbstractContentAction;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Application;

/**
 * Class AbstractAction
 * @package Action
 *
 * @method \Moro\Platform\Model\Implementation\Content\ServiceContent getService()
 */
abstract class AbstractAction extends AbstractContentAction
{
	const THEME_BLACK = 'g-theme_black';
	const THEME_WHITE = 'g-theme_white';

	public $serviceCode = Application::SERVICE_CONTENT;

	/**
	 * @var int
	 */
	protected $_status = 200;

	/**
	 * @var array
	 */
	protected $_headers = [
		'Content-Type' => 'text/html; charset=utf-8',
	];

	/**
	 * @param Application $app
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function __invoke(Application $app, Request $request)
	{
		$args = func_get_args();
		$this->setApplication(array_shift($args));
		$this->setRequest(array_shift($args));

		return $this->getService()->with($this->_getDecorator(), function() use ($app, $args) {
			$parameters = (array)call_user_func_array([$this, 'execute'], $args);

			return $this->template
				? $app->render($this->template, $parameters, new Response('', $this->_status, $this->_headers))
				: $app->json($parameters, $this->_status, $this->_headers);
		});
	}

	/**
	 * @return \Moro\Platform\Model\Implementation\Content\Decorator\AbstractDecorator
	 */
	protected function _getDecorator()
	{
		return new ViewContentDecorator($this->getApplication());
	}
}