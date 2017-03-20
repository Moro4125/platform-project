<?php
/**
 * Class Application
 */
use \Moro\Platform\Application as CApplication;

/**
 * Class Application
 */
class Application extends CApplication
{
	protected $NAME    = 'Project';
	protected $VERSION = '0.0';

	/**
	 * Generates an absolute URL from the given parameters.
	 *
	 * @param string $route      The name of the route
	 * @param mixed  $parameters An array of parameters
	 *
	 * @return string The generated URL
	 */
	public function url($route, $parameters = array())
	{
		if ($pos = strpos($route, '?'))
		{
			list($route, $query) = explode('?', $route, 2);
			parse_str($query, $temp);
			$parameters = array_merge($temp, $parameters);
		}

		if (in_array($route, ['news', 'posts']))
		{
			if (empty($parameters['page']) || $parameters['page'] == 1)
			{
				$parameters['page'] = '';
			}
			else
			{
				$parameters['page'] = rtrim($parameters['page'], '/').'/';
			}
		}

		return parent::url($route, $parameters);
	}
}

Application::getInstance();