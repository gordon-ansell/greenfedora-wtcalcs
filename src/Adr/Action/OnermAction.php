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
use WTCalcs\Adr\Responder\OnermResponder;
use GreenFedora\Payload\Payload;
use GreenFedora\Http\CookieHandler;

use WTCalcs\Adr\Domain\OnermCalcs;

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
        $payload = new Payload();
        $cookieHandler = new CookieHandler($this->input, array('weight' => 100, 'reps' => 2, 'rounding' => 2.5), 'onerm_');
        $cookieHandler->load($payload);

        // Has user posted the form?
        if ($this->input->isPost()) {
            $payload->set('weight', floatval($this->input->post('weight', 100)));
            $payload->set('reps', intval($this->input->post('reps', 2)));
            $payload->set('rounding', floatval($this->input->post('rounding', 2.5)));

            $results = array();
            $calculator = new OnermCalcs();
            $average = $calculator->onermcalcs($payload->weight, $payload->reps, $payload->rounding, $results);

            $payload->set('results', $results);
            $payload->set('average', $average);

            $cookieHandler->save($payload);
        }

        $responder = new OnermResponder($this->container, $this->output, $payload);
        $responder->dispatch();
    }

}
