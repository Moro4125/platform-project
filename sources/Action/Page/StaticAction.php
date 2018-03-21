<?php
/**
 * Class StaticAction
 */
namespace Action\Page;
use \Action\AbstractArticleAction;
use \Symfony\Component\HttpFoundation\Request;
use \Application;

/**
 * Class StaticAction
 * @package Action\Page
 */
class StaticAction extends AbstractArticleAction
{
	public $route = 'static';
	public $theme = self::THEME_BLACK;
	public $template = 'page/static.html.twig';

	/**
	 * @param Application $app
	 * @param Request $request
	 * @param string $code
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function __invoke(Application $app, Request $request, $code = null)
	{
		return parent::__invoke($app, $request, 'pages', $code);
	}
}