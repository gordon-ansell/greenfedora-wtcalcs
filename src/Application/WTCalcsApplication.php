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
	}
	
}
