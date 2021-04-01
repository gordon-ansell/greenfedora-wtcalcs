<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Responder;

use GreenFedora\Adr\Responder\AbstractResponder;
use GreenFedora\Adr\Responder\ResponderInterface;

/**
 * The index responder.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class IndexResponder extends AbstractResponder implements ResponderInterface
{
    /**
     * Dispatch the responder.
     */
    public function dispatch()
    {
        $r = $this->container->get('template')->render('index', $this->payload->toArray());
        $this->output->setBody($r);
    }
}
