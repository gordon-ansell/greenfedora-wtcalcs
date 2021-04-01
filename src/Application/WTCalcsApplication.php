<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Application;

use GreenFedora\Application\AbstractHttpApplication;
use GreenFedora\Application\ApplicationInterface;
use GreenFedora\Application\Input\ApplicationInputInterface;
use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\Router\Router;
use GreenFedora\Template\PlatesTemplate;

/**
 * The main WTCalcs application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WTCalcsApplication extends AbstractHttpApplication implements ApplicationInterface
{

	/**
	 * Constructor.
	 *
	 * @param	ApplicationInputInterface	$input 		Input.
	 * @param	ApplicationOutputInterface	$output 	Output.
	 * @param	string						$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 *
	 * @return	void
	 */
	public function __construct(ApplicationInputInterface $input, ApplicationOutputInterface $output, string $mode = 'prod')
	{
		parent::__construct($input, $output, $mode);
	}

	/**
	 * Run.
	 *
	 * @return 	void
	 */
	protected function run()
	{
		$this->trace($this->x("WTCalcs version %s started.", APP_VERSION));

		$this->dispatch();
	}
	
}
