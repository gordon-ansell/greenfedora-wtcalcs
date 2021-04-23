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
use GreenFedora\Payload\Payload;
use WTCalcs\Adr\Responder\NotFoundResponder;

/**
 * The not found action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class NotFoundAction extends AbstractAction implements ActionInterface
{

    /**
     * Dispatch the action.
     */
    public function dispatch()
    {
        $payload = new Payload();
        $responder = new NotFoundResponder($this->container, $this->request, $this->response, $payload);
        $responder->dispatch();
    }

}
