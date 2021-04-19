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
//use GreenFedora\DependencyInjection\ContainerInterface;

//use GreenFedora\DependencyInjection\Container;
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

use GreenFedora\DI\Container;
use GreenFedora\DI\ContainerInterface;


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
		// Container instance.
		$di = Container::getInstance();

		// Config.
		$di->setSingletonAndCreate('config', Config::class)->process($env);

		// Locale.
		$di->setSingleton('locale', Locale::class, [$di->get('config')->locale]);

		// Logger.
		$di->setValue('loggerConfig', $di->get('config')->logger);
		$di->setClass('logFormatter', StdLogFormatter::class);
		$writers = array($di->create(FileLogWriter::class));
		if ('prod' != $env) {
			$writers[] = $di->create(ForcedConsoleLogWriter::class);		
		}
		$di->setValue('logWriters', $writers);
		$di->setSingleton('logger', Logger::class);

		$di->get('logger')->error("Testing");

		// Inflector.
		$di->setClass('inflector', Inflector::class);

		// Lang.
		$di->setClass('lang', Lang::class, [$di->get('locale')->getLangCode()]);

		// Session.
		$di->setClass('session', Session::class, [$di->get('config')->session]);

		// Router.
		$di->setClass('router', Router::class, [$di->get('config')->routing, $di]);

		// Template.
		$tplType = $di->get('config')->templateType;
		if ('plates' == $tplType) {
			$di->setClass('template', PlatesTemplate::class, [$di->get('config')->template, $di]);
		} else if ('smarty' == $tplType) {
			$di->setClass('template', SmartyTemplate::class, [$di->get('config')->template, $di]);
		} else {
			throw new \InvalidArgumentException(sprintf("No template support for type '%s'", $tplType));
		}

		return $di;

		// =================================

		/*
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
		*/
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
