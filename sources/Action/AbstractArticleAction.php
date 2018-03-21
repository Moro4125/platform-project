<?php
/**
 * Class AbstractArticleAction
 */
namespace Action;
use \Model\ViewContentDecorator;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Application;

/**
 * Class AbstractArticleAction
 * @package Action
 */
abstract class AbstractArticleAction extends AbstractAction
{
	/**
	 * @var string
	 */
	public $theme = self::THEME_BLACK;

	/**
	 * @param string $heading
	 * @param string $code
	 * @return array
	 */
	public function execute($heading, $code)
	{
		$service = $this->getService();

		/** @var \Moro\Platform\Model\Implementation\Content\Decorator\HeadingDecorator $entity */
		if (!$entity = $service->getEntityByCode($code, true))
		{
			throw new NotFoundHttpException(sprintf('Article with code "%1$s" not found.', $code));
		}

		if ($heading != $entity->getHeading())
		{
			throw new NotFoundHttpException(sprintf('Article with code "%1$s" has another heading.', $code));
		}

		$entityId = $entity->getId();
		$next = $service->getEntityByChain($entity, false);
		$prev = $service->getEntityByChain($entity, true);

		$articles = $service->filterIdList($entity->selectParameter('articles', []));
		$list = $this->_getNearList($entity, $articles);
		$arts = [];

		foreach ($articles as $id)
		{
			/** @var $item ViewContentDecorator */
			if (($item = $service->getEntityById($id, true)) && $item->getUrl())
			{
				unset($list['id'.$id]);
				$arts['id'.$id] = $item;
			}
		}

		uasort($arts, function(ViewContentDecorator $a, ViewContentDecorator $b) {
			return $b->getOrderAt() - $a->getOrderAt();
		});
		$list = array_merge($arts, $list);

		$keywords = [$entity->getHeadingName()];

		foreach ($entity->getTags() as $tag)
		{
			$keywords[] = ($pos = strpos($tag, ':'))
				? ltrim(substr($tag, $pos + 1))
				: $tag;
		}

		$this->_headers[Application::HEADER_CACHE_TAGS] = 'art-'.$entityId;

		foreach ($list as $item)
		{
			$this->_headers[Application::HEADER_CACHE_TAGS].= ',art-'.$item['id'];
		}

		$next && $this->_headers[Application::HEADER_CACHE_TAGS].= ',art-'.$next['id'];
		$prev && $this->_headers[Application::HEADER_CACHE_TAGS].= ',art-'.$prev['id'];

		$attachmentList = $service->selectAttachmentByEntity($entity);

		return [
			'theme'    => $this->theme,
			'title'    => $entity->getName(),
			'article'  => $entity,
			'keywords' => $keywords,
			'items'    => array_slice($list, 0, 6),
			'next'     => $next,
			'prev'     => $prev,
			'files'    => $attachmentList,
		];
	}

	/**
	 * @param \Moro\Platform\Model\Implementation\Content\Decorator\HeadingDecorator $entity
	 * @param array $articles
	 * @return \Moro\Platform\Model\Implementation\Content\Decorator\HeadingDecorator[]
	 */
	protected function _getNearList($entity, $articles)
	{
		$service  = $this->getService();
		$entityId = $entity->getId();
		$heading  = $entity->getHeading();

		$temp = $entity->getParameters();
		$tags = array_unique(array_merge(isset($temp['tags']) ? $temp['tags'] : [], ['Флаг: опубликовано']));
		$plus = 1 + count($articles);

		do
		{
			$list = $service->selectEntities(0, 6 + $plus, '!order_at', '~tag', implode(',', $tags));
			$flag = count($list) == 6 + $plus;
			$list = array_filter($list, function(ViewContentDecorator $item) use ($entityId, $heading) {
				/** @var \Moro\Platform\Model\Implementation\Content\Decorator\HeadingDecorator $item */
				return $item->getId() != $entityId && $item->getHeading() == $heading;
			});
		}
		while ($plus < 6 + $plus - count($list) && $plus++ && $flag);

		return $list;
	}
}