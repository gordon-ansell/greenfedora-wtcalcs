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
use GreenFedora\Lang\Lang;
use GreenFedora\Inflector\Inflector;
use GreenFedora\Router\Router;
use GreenFedora\Template\PlatesTemplate;
use GreenFedora\Template\SmartyTemplate;
use GreenFedora\Session\Session;
use GreenFedora\Logger\LoggerInterface;
use GreenFedora\Lang\LangAwareInterface;
use GreenFedora\Lang\LangAwareTrait;
use GreenFedora\Lang\LangInterface;

/**
 * The main WTCalcs application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WTCalcsApplication extends AbstractHttpApplication implements HttpApplicationInterface, LangAwareInterface
{
	use LangAwareTrait;

	/**
	 * Bootstrap.
	 * 
	 * @return 	HttpApplicationInterface
	 */
	public function bootstrap(): HttpApplicationInterface
	{
		// Inflector.
		$this->addClass('inflector', Inflector::class);

		// Lang.
		$this->addClass('lang', Lang::class, [$this->get('locale')->getLangCode()]);

		// Session.
		$this->addClass('session', Session::class, [$this->get('config')->session]);

		// Router.
		$this->addClass('router', Router::class, [$this->get('config')->routing]);

		// Template.
		$tplType = $this->get('config')->templateType;
		if ('plates' == $tplType) {
			$this->addClass('template', PlatesTemplate::class, [$this->get('config')->template]);
		} else if ('smarty' == $tplType) {
			$this->addClass('template', SmartyTemplate::class, [$this->get('config')->template, $this]);
		} else {
			throw new \InvalidArgumentException(sprintf("No template support for type '%s'", $tplType));
		}

		return $this;

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
