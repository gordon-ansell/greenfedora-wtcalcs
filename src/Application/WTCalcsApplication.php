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
	 * @return 	void
	 */
	protected function run()
	{
		$this->trace($this->x("WTCalcs version %s started.", APP_VERSION));

		// Create the router.
		$router = $this->getRouter();

		// Find a match for the route.
		$matched = $router->match($this->input->getRoute());

		$this->trace4(sprintf("Matched namespaced class is: %s", $matched->getNamespacedClass()));

		$class = $matched->getNamespacedClass();
		$dispatchable = new $class();

		$dispatchable->dispatch();
	}
	
}
