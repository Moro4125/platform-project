<?php
/**
 * Class IndexAction
 */
namespace Action\Page;
use \Action\AbstractAction;
use \Application;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class IndexAction
 * @package Action\Tools
 */
class IndexAction extends AbstractAction
{
	const MSG_HEADING_NOT_FOUND = 'Heading "%1$s" is not exists.';

	public $route    = 'index';
	public $template = 'page/index.html.twig';

	/**
	 * @return array
	 */
	public function execute()
	{
		$results = [
			'title' => 'Hello, world!',
		];
		$this->_headers[Application::HEADER_CACHE_TAGS] = 'index,options';

		$app = $this->getApplication();
		$service = $this->getService();

		foreach (['news', 'posts'] as $headingCode)
		{
			if (!$headings = $app->getServiceTags()->selectEntities(null, 10, null, 'tag', 'heading:'.$headingCode))
			{
				throw new NotFoundHttpException(sprintf(self::MSG_HEADING_NOT_FOUND, $headingCode));
			}

			/** @var \Moro\Platform\Model\Implementation\Tags\TagsInterface $heading */
			$heading = reset($headings);
			$heading = $heading->getProperties();
			$heading['code'] = $headingCode;
			$heading['name'] = trim(explode(':', $heading['name'])[1]);

			$items = $service->selectEntities(0, 10, '!order_at', 'heading', $headingCode);
			$results[$headingCode] = [
				'heading' => $heading,
				'items' => $items,
			];
		}

		return $results;
	}
}