<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Application;

use GreenFedora\Application\AbstractHttpApplication;
use GreenFedora\Application\HttpApplicationInterface;
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
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\LoggerAwareInterface;
use GreenFedora\Logger\LoggerInterface;
use GreenFedora\Lang\LangAwareInterface;
use GreenFedora\Lang\LangAwareTrait;
use GreenFedora\Lang\LangInterface;

/**
 * The main WTCalcs application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WTCalcsApplication extends AbstractHttpApplication implements HttpApplicationInterface, LoggerAwareInterface,
	LangAwareInterface
{
	use LoggerAwareTrait;
	use LangAwareTrait;

	/**
	 * Bootstrap.
	 * 
	 * @return 	HttpApplicationInterface
	 */
	public function bootstrap(): HttpApplicationInterface
	{
		
		// Config.
		$this->setSingletonAndCreate('config', Config::class)->process($this->mode);

		// Locale.
		$this->setSingleton('locale', Locale::class, [$this->get('config')->locale]);

		// Logger.
		$this->setInjectableValue('loggerConfig', $this->get('config')->logger);
		$this->setClass('logFormatter', StdLogFormatter::class);
		$writers = array($this->create(FileLogWriter::class));
		if ('prod' != $this->mode) {
			$writers[] = $this->create(ForcedConsoleLogWriter::class);		
		}
		$this->setInjectableValue('logWriters', $writers);
		$this->setSingleton('logger', Logger::class);

		// Inflector.
		$this->setClass('inflector', Inflector::class);

		// Lang.
		$this->setClass('lang', Lang::class, [$this->get('locale')->getLangCode()]);

		// Session.
		$this->setClass('session', Session::class, [$this->get('config')->session]);

		// Router.
		$this->setClass('router', Router::class, [$this->get('config')->routing]);

		// Template.
		$tplType = $this->get('config')->templateType;
		if ('plates' == $tplType) {
			$this->setClass('template', PlatesTemplate::class, [$this->get('config')->template]);
		} else if ('smarty' == $tplType) {
			$this->setClass('template', SmartyTemplate::class, [$this->get('config')->template, $this]);
		} else {
			throw new \InvalidArgumentException(sprintf("No template support for type '%s'", $tplType));
		}

		return $this;

	}

	/**
	 * Get the logger.
	 * 
	 * @return 	LoggerInterface
	 */
	public function getLogger(): LoggerInterface
	{
		return $this->get('logger');
	}

	/**
	 * Get the language processot.
	 * 
	 * @return 	LangInterface
	 */
	public function getLang(): LangInterface
	{
		return $this->get('lang');
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
