<?php
/**
 * Class IndexAction
 */
namespace Action\Page;
use \Action\AbstractAction;
use \Application;

/**
 * Class IndexAction
 * @package Action\Tools
 */
class IndexAction extends AbstractAction
{
	public $route    = 'index';
	public $template = 'http/index.html.twig';

	/**
	 * @return array
	 */
	public function execute()
	{
		$this->_headers[Application::HEADER_CACHE_TAGS] = 'index,options';

		return [
			'title' => 'Hello, world!',
		];
	}
}