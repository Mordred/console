<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Console;

use Nette\DI\Container;

/**
 * Lazy application inicialization router
 *
 * For use Symfony Console
 *
 * @author	Patrik Votoček
 */
class LazyRouter extends Router
{
	/**
	 * @param \Nette\DI\Container
	 */
	public function __construct(Container $container, $serviceName = NULL)
	{
		if (!$serviceName) {
			$class = 'Symfony\Component\Console\Application';
			$names = $container->findByType($class);
			if (!$names) {
				throw new \Nette\DI\MissingServiceException("Service of type $class not found.");
			} elseif (count($names) > 1) {
				throw new \Nette\DI\MissingServiceException("Multiple services of type $class found.");
			} else {
				$serviceName = $names[0];
			}
		}

		if (!$container->hasService($serviceName)) {
			throw new \Nette\DI\MissingServiceException("Service '$serviceName' not found.");
		}

		$this->callback = callback(function () use ($container, $serviceName) {
			$console = $container->getService($serviceName);
			$console->run();
		});
	}
}

