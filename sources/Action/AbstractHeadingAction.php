<?php
/**
 * Class AbstractHeadingAction
 */
namespace Action;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Application;

/**
 * Class AbstractHeadingAction
 * @package Action
 */
abstract class AbstractHeadingAction extends AbstractAction
{
	const MSG_HEADING_NOT_FOUND = 'Heading "%1$s" is not exists.';
	const MSG_PAGE_NOT_FOUND = 'Page with number "%1$s" for heading "%2$s" is not exists.';

	/**
	 * @var string
	 */
	public $theme = self::THEME_BLACK;

	/**
	 * @var int
	 */
	protected $_count = 6;

	/**
	 * @param string $headingCode
	 * @param int $page
	 * @return array
	 */
	public function execute($headingCode, $page)
	{
		$app = $this->getApplication();

		if (!$headings = $app->getServiceTags()->selectEntities(null, null, null, 'tag', 'heading:'.$headingCode))
		{
			throw new NotFoundHttpException(sprintf(self::MSG_HEADING_NOT_FOUND, $headingCode));
		}

		/** @var \Moro\Platform\Model\Implementation\Tags\TagsInterface $heading */
		$heading = reset($headings);
		$heading = $heading->getProperties();
		$heading['code'] = $headingCode;
		$heading['name'] = trim(explode(':', $heading['name'])[1]);

		$service = $this->getService();
		$offset = ($page - 1) * $this->_count;
		$items = $service->selectEntities($offset, $this->_count, '!order_at', 'heading', $headingCode);
		$count = $service->getCount('heading', $headingCode);
		$pages = ceil(($count ?: 1) / $this->_count);

		if ($page > $pages)
		{
			throw new NotFoundHttpException(sprintf(self::MSG_PAGE_NOT_FOUND, $headingCode, $page));
		}

		$this->_headers[Application::HEADER_CACHE_TAGS] = 'heading-'.$headingCode;

		foreach ($items as $item)
		{
			$this->_headers[Application::HEADER_CACHE_TAGS].= ',art-'.$item->getId();
		}

		if ($first = $service->getEntityByCode($headingCode.'-description', true))
		{
			$this->_headers[Application::HEADER_CACHE_TAGS].= ',art-'.$first->getId();
		}

		return [
			'theme'   => $this->theme,
			'title'   => $heading['name'].($page > 1 ? ' (стр.'.$page.') ' : ' '),
			'heading' => $heading,
			'first'   => $first,
			'items'   => $items,
			'page'    => $page,
			'pages'   => $pages,
		];
	}
}