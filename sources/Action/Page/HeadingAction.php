<?php
/**
 * Class HeadingAction
 */
namespace Action\Page;
use \Action\AbstractHeadingAction;
use \Symfony\Component\HttpFoundation\Request;
use \Application;

/**
 * Class HeadingAction
 * @package Action\Page
 */
class HeadingAction extends AbstractHeadingAction
{
	public $route    = 'heading';
	public $template = 'page/heading.html.twig';
	public $theme    = self::THEME_BLACK;

	/**
	 * @param Application $app
	 * @param Request $request
	 * @param string $heading
	 * @param string $page
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function __invoke(Application $app, Request $request, $heading = null, $page = null)
	{
		return parent::__invoke($app, $request, $heading, max(1, (int)$page));
	}
}