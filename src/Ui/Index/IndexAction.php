<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Ui\Index;

use GreenFedora\Http\Adr\AbstractHttpAction;
use GreenFedora\Application\Adr\ActionInterface;
use GreenFedora\Payload\Payload;
use GreenFedora\Application\ResponseInterface;
use WTCalcs\Ui\Index\IndexResponder;

/**
 * The index action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class IndexAction extends AbstractHttpAction implements ActionInterface
{

    /**
     * Dispatch the action.
     * 
     * @return HttpResponseInterface
     */
    public function dispatch(): ResponseInterface
    {
        $payload = new Payload();
        $responder = new IndexResponder($this->container, $this->request, $this->response, $payload);
        return $responder->respond();
    }

}
