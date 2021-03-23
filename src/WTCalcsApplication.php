<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs;

use GreenFedora\Application\ConsoleApplication;
use GreenFedora\Application\ApplicationInterface;
use GreenFedora\Application\Input\ApplicationInputInterface;
use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrInterface;
use GreenFedora\FileSystem\Yaml\YamlFile;
use GreenFedora\FileSystem\DirIter;
use GreenFedora\FileSystem\FileInfo;
use GreenFedora\Uri\Uri;
use GreenFedora\FileSystem\DirIterFilter\Filter;
use GreenFedora\Markdown\ParsedownProcessor;
use GreenFedora\Template\TemplateInterface;
use GreenFedora\Template\SmartyTemplate;

/**
 * The main WTCalcs application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WTCalcsApplication extends ConsoleApplication implements ApplicationInterface
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
        echo "HERE";
		
		// Set the output for return.
		$output->setOutput(0);
	}
	
}
