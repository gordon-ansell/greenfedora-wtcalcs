<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Ui\NotFound;

use GreenFedora\Http\Adr\AbstractHttpAction;
use GreenFedora\Application\Adr\ActionInterface;
use GreenFedora\Payload\Payload;
use WTCalcs\Ui\NotFound\NotFoundResponder;
use GreenFedora\Application\ResponseInterface;

/**
 * The not found action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class NotFoundAction extends AbstractHttpAction implements ActionInterface
{

    /**
     * Dispatch the action.
     * 
     * @return HttpResponseInterface
     */
    public function dispatch(): ResponseInterface
    {
        $payload = new Payload();
        $responder = new NotFoundResponder($this->container, $this->request, $this->response, $payload);
        return $responder->respond();
    }

}
