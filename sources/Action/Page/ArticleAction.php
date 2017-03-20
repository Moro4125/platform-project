<?php
/**
 * Class ArticleAction
 */
namespace Action\Page;
use \Action\AbstractArticleAction;
use \Symfony\Component\HttpFoundation\Request;
use \Application;

/**
 * Class ArticleAction
 * @package Action\Page
 */
class ArticleAction extends AbstractArticleAction
{
	public $route = 'article';
	public $theme = self::THEME_BLACK;
	public $template = 'page/article.html.twig';

	/**
	 * @param Application $app
	 * @param Request $request
	 * @param string $heading
	 * @param string $code
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function __invoke(Application $app, Request $request, $heading = null, $code = null)
	{
		return parent::__invoke($app, $request, $heading, $code);
	}
}