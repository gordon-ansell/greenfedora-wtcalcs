<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Action;

use GreenFedora\Adr\Action\AbstractAction;
use GreenFedora\Adr\Action\ActionInterface;

use WTCalcs\Adt\Responder\OnermResponder;

/**
 * The 1-rep maximum calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class OnermAction extends AbstractAction implements ActionInterface
{
    /**
     * Dispatch the action.
     */
    public function dispatch()
    {
        echo "Dispatched action." . PHP_EOL . '<br />';

        $responder = new OnermResponder($this->container, $this->output);
    }
}
