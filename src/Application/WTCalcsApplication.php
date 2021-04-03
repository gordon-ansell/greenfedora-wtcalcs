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
use GreenFedora\DependencyInjection\ContainerInterface;

use GreenFedora\DependencyInjection\Container;
use GreenFedora\Config\Config;
use GreenFedora\Locale\Locale;
use GreenFedora\Logger\Logger;
use GreenFedora\Logger\Formatter\StdLogFormatter;
use GreenFedora\Logger\Writer\FileLogWriter;
use GreenFedora\Logger\Writer\ForcedConsoleLogWriter;
use GreenFedora\Lang\Lang;
use GreenFedora\Inflector\Inflector;
use GreenFedora\Router\Router;
use GreenFedora\Template\PlatesTemplate;
use GreenFedora\Template\SmartyTemplate;
use GreenFedora\Session\Session;


/**
 * The main WTCalcs application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WTCalcsApplication extends AbstractHttpApplication implements ApplicationInterface
{

	/**
	 * Bootstrap.
	 * 
	 * @param 	string 				$env 	Environment.
	 * @return 	ContainerInterface
	 */
	static public function bootstrap(string $env): ContainerInterface
	{
		// Load up the service manager.
		$container = new Container();

		// Config, locale.
		$container->create(Config::class, [], 'config')->process($env);
		$container->create(Locale::class, [$container->get('config')->locale], 'locale');

		// Logger.
		$lcfg = $container->get('config')->logger;
		$formatter = new StdLogFormatter($lcfg);
		$writers = array(new FileLogWriter($lcfg, $formatter));
		if ('prod' != $env) {
			$writers[] = new ForcedConsoleLogWriter($lcfg, $formatter);		
		}
		$container->create(Logger::class, [$lcfg, $writers], 'logger');

		// Lang, inflector.
		$container->create(Lang::class, [$container->get('locale')->getLangCode()], 'lang');
		$container->create(Inflector::class, [], 'inflector');

		//Session.
		$container->create(Session::class, [$container->get('config')->session], 'session');

		// Router.
		$container->create(Router::class, [$container->get('config')->routing, $container], 'router');

		// Template.
		$tplType = $container->get('config')->templateType;
		if ('plates' == $tplType) {
			$container->create(PlatesTemplate::class, [$container->get('config')->template, $container], 'template');
		} else if ('smarty' == $tplType) {
			$container->create(SmartyTemplate::class, [$container->get('config')->template, $container], 'template');
		} else {
			throw new \InvalidArgumentException(sprintf("No template support for type '%s'", $tplType));
		}

		return $container;
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
