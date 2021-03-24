<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Application;

use GreenFedora\Application\HttpApplication;
use GreenFedora\Application\ApplicationInterface;
use GreenFedora\Application\Input\ApplicationInputInterface;
use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\Router\Router;

/**
 * The main WTCalcs application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WTCalcsApplication extends HttpApplication implements ApplicationInterface
{
	/**
	 * Run.
	 *
	 * @param	ApplicationInputInterface	$input 		Input.
	 * @param	ApplicationOutputInterface	$output 	Output.
	 *
	 * @return 	void
	 */
	protected function run(ApplicationInputInterface $input, ApplicationOutputInterface $output)
	{
		$this->trace($this->x("WTCalcs version %s started.", APP_VERSION));

		// Create the router.
		$router = $this->getRouter();

		// Find a match for the route.
		$matched = $router->match($input->getRoute());

		$this->trace4(sprintf("Matched namespaced class is: %s", $matched->getNamespacedClass()));

		$class = $matched->getNamespacedClass();
		$dispatchable = new $class();

		$dispatchable->dispatch();


		// Create the namespaced class.
		/*
		$class = '';

		if ('\\' != $matched[1]) {
			if ($this->getConfig('routing')->prefixNamespace) {
				$class = $this->getConfig('routing')->prefixNamespace;
			}
		}
		*/

		/*
		print_r($input->getRoute() . PHP_EOL . '<br />');
		echo PHP_EOL . '<br />';
		echo $this->getConfig('routing')->prefixNamespace . PHP_EOL . '<br />';
		echo PHP_EOL . '<br />';
		print_r($this->getConfig('routing')->routes->toArray());
		echo PHP_EOL . '<br />';

		$target = 'OnermAction';
		$class = '';

		if ('\\' != $target[0]) {
			if ($this->getConfig('routing')->prefixNamespace) {
				$class = $this->getConfig('routing')->prefixNamespace;
			}
		}

		if ('\\' != $class[0]) {
			$class = '\\' . $class;
		}

		$class = '\\' . trim($class, '\\') . '\\' . trim($target, '\\');

		echo $class;

		$t = new $class();
		$t->dispatch();
		*/

	}
	
}
