<?php
/**
 * Class InnerAction
 */
namespace Action\Tools;
use \Action\AbstractAction;
use \Symfony\Component\HttpFoundation\Request;
use \Application;

/**
 * Class InnerAction
 * @package Action\Tools
 */
class InnerAction extends AbstractAction
{
	public $route    = 'inner';
	public $template = 'block/%1$s.html.twig';

	/**
	 * @param Application $application
	 * @param Request $request
	 * @param string $code
	 * @param string $theme
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function __invoke(Application $application, Request $request, $code = null, $theme = null)
	{
		$parameters = ['theme' => $theme];
		$codes = explode('.', $code);

		foreach ($codes as $index => $value)
		{
			$parameters['arg'.$index] = $value;
		}

		return $application->render(sprintf($this->template, reset($codes)), $parameters);
	}
}