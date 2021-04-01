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
use GreenFedora\Validator\Compulsory;
use GreenFedora\Validator\Numeric;
use GreenFedora\Validator\Integer;
use GreenFedora\Validator\NumericBetween;
use GreenFedora\Filter\FloatVal;
use GreenFedora\Filter\IntVal;
use GreenFedora\Form\FormValidator;

use WTCalcs\Adr\Domain\Onerm\OnermCalcs;

/**
 * The 1-rep maximum calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class OnermAction extends AbstractAction implements ActionInterface
{
    /**
     * Validation.
     * 
     * @return  null|array         Null if it worked, else error message and failed field.
     */
    protected function validate(): ?array
    {

        $fv = new FormValidator();
        $fv->addFilter('weight', new FloatVal())
            ->addValidator('weight', new Compulsory(['weight']))
            ->addValidator('weight', new NumericBetween(['weight'], array('low' => 5, 'high' => 9999.99)));

        $fv->addValidator('reps', new Compulsory(['reps']))
            ->addValidator('reps', new Integer(['reps']))
            ->addValidator('reps', new NumericBetween(['reps'], array('low' => 2, 'high' => 15)));

        $fv->addFilter('rounding', new FloatVal())
            ->addValidator('rounding', new Compulsory(['rounding']))
            ->addValidator('rounding', new NumericBetween(['weight'], array('low' => 0.01, 'high' => 20)));

        $result = $fv->validate($this->input->post()->toArray(), ['weight', 'reps', 'rounding']);

        if (null === $result) {
            return $result;
        } else {
            return [$result, $fv->getFailedField()];
        }
    }

    /**
     * Dispatch the action.
     */
    public function dispatch()
    {
        $payload = new Payload();
        $cookieHandler = new CookieHandler($this->input, array('weight' => '', 'reps' => 2, 'rounding' => 2.5), 'onerm_');
        $cookieHandler->load($payload);

        $payload->set('error', '');
        $payload->set('af', 'weight');
        $payload->set('results', []);

        // Has user posted the form?
        if ($this->input->isPost()) {

            $payload->set('weight', $this->input->post('weight', ''));
            $payload->set('reps', $this->input->post('reps', ''));
            $payload->set('rounding', $this->input->post('rounding', 2.5));

            $error = $this->validate();
            if (null !== $error) {
                $payload->set('error', $error[0]);
                $payload->set('af', $error[1]);
            } else {

                $results = array();
                $calculator = new OnermCalcs();
                $average = $calculator->onermcalcs(floatval($payload->weight), intval($payload->reps), 
                    floatval($payload->rounding), $results);

                $payload->set('results', $results);
                $payload->set('average', $average);
            }

            $cookieHandler->save($payload);
        }

        $responder = new OnermResponder($this->container, $this->output, $payload);
        $responder->dispatch();
    }

}
