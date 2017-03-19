<?php
/**
 * Class ViewContentDecorator
 */
namespace Model;
use \Moro\Platform\Model\Implementation\Content\Decorator\HeadingDecorator;
use \Moro\Platform\Model\Implementation\Content\Decorator\ViewDecorator;
use \Moro\Platform\Application;

/**
 * Class ViewContentDecorator
 * @package Model
 */
class ViewContentDecorator extends ViewDecorator
{
	/**
	 * @var array
	 */
	protected $_imageViews = [
		self::IMG_VIEW_HORIZONTAL => [ 'width' => 624, 'height' => 390 ],
		self::IMG_VIEW_VERTICAL   => [ 'width' => 450, 'height' => 720 ],
		self::IMG_VIEW_SQUARE     => [ 'width' => 624, 'height' => 624 ],
	];

	/**
	 * @var float
	 */
	protected $_priority = 1;

	/**
	 * @var array
	 */
	protected $_urlParameters;

	/**
	 * @var string
	 */
	protected $_url;

	/**
	 * @var string
	 */
	protected $_rootHeading;

	/**
	 * @var array
	 */
	protected static $_map = [
		'holster'     => ['article_w', 'heading_w'],
		'pounch'      => ['article_w', 'heading_w'],
		'accessories' => ['article_w', 'heading_w'],
		'knife'       => ['article_w', 'heading_w'],
		'paracord'    => ['article_w', 'heading_w'],
		'maxpedition' => ['article_e', 'heading_e'],
		'pistol'      => ['article_h', 'heading_h'],
		'instructions'=> ['article_h', 'heading_h'],
	];

	/**
	 * @var array
	 */
	protected static $_root = [
		'heading_w' => 'workshop',
		'heading_e' => 'equipment',
		'heading_h' => 'handbook',
	];

	/**
	 * @var HeadingDecorator
	 */
	protected $_entity;

	/**
	 * ViewContentDecorator constructor.
	 * @param Application $application
	 * @param float|null $priority
	 */
	public function __construct(Application $application, $priority = null)
	{
		parent::__construct($application, $priority);
		$this->decorate(new HeadingDecorator($application, 0.5));
	}

	/**
	 * @return string
	 */
	public function getHeading()
	{
		return $this->_entity->getHeading();
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		if (empty($this->_url) && $heading = $this->_entity->getHeading())
		{
			$parameters = $this->getParameters();

			$this->_url = empty($parameters['link'])
				? $this->_application->url(self::$_map[$heading][0], array_merge((array)$this->_urlParameters, [
					self::$_map[$heading][1] => $heading,
					'code'                   => $this->getCode(),
				]))
				: $parameters['link'];
		}

		return (string)$this->_url;
	}

	/**
	 * @return string
	 */
	public function getRootHeading()
	{
		if (empty($this->_rootHeading))
		{
			$heading = $this->_entity->getHeading();
			$this->_rootHeading = self::$_root[self::$_map[$heading][1]];
		}

		return $this->_rootHeading;
	}
}