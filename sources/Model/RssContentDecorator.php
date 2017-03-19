<?php
/**
 * Class RssContentDecorator
 */
namespace Model;
use \Michelf\MarkdownExtra;

/**
 * Class RssContentDecorator
 * @package Model
 */
class RssContentDecorator extends ViewContentDecorator
{
	/**
	 * @return string HTML
	 */
	public function getDescription()
	{
		$html = '';
		$url = htmlspecialchars($this->getUrl());
		if ($hash = $this->getIcon())
		{
			$imgUri = $this->_application->url('image', ['hash' => $hash, 'width' => 600, 'height' => 315]);
			$html .= '<a href="'.$url.'"><img src="'.$imgUri.'"/></a>';
		}
		else
		{
			$code = $this->getCode();
			$message = "В RSS попал материал с кодом \"$code\", который не содержит изображения для анонса.";
			$this->_application->getServiceFlash()->error($message);
		}
		$parameters = $this->getParameters();
		if (!empty($parameters['lead']))
		{
			$html .= MarkdownExtra::defaultTransform($parameters['lead']);
		}
		return $html;
	}
}