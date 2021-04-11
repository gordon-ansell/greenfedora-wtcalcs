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
use GreenFedora\Validator\Compulsory;
use GreenFedora\Validator\Numeric;
use GreenFedora\Validator\Integer;
use GreenFedora\Validator\NumericBetween;
use GreenFedora\Filter\FloatVal;
use GreenFedora\Filter\IntVal;
use GreenFedora\Form\FormPersistHandler;
use GreenFedora\Form\FormPersistHandlerInterface;
use GreenFedora\Form\Form;
use GreenFedora\Html\Html;

use WTCalcs\Adr\Domain\Onerm\OnermCalcs;

/**
 * The 1-rep maximum calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class OnermAction extends AbstractAction implements ActionInterface
{

    /**
     * Create the form.
     * 
     * @return  FormInterface
     */
    protected function createForm()
    {
        $ph = new FormPersistHandler($this->getInstance('session'), $this->input, 
            array('weight' => '', 'reps' => 2, 'rounding' => 2.5), 'onerm_');

        $form = new Form('/onerm', $ph);
        $form->setAutoWrap('fieldset');

        $form->addField('errors', ['name' => 'errors', 'class' => 'error']);
        $form->addField('divopen', ['name' => 'row1', 'class' => 'three-columns-always']);

            $form->addField('inputtext', ['name' => 'weight', 'label' => 'Weight', 
                'placeholder' => 'Weight', 'title' => "Enter the weight you lifted (5-9999.99)."])
                ->addFilter(new FloatVal())
                ->addValidator(new Compulsory(['weight']))
                ->addValidator(new NumericBetween(['weight'], array('low' => 5, 'high' => 9999.99)));

            $form->addField('inputtext', ['name' => 'reps', 'label' => 'Reps', 
                'title' => "Enter the number of reps you performed (1-15).", 'style' => "width: 4em;"])
                ->addValidator(new Compulsory(['reps']))
                ->addValidator(new Integer(['reps']))
                ->addValidator(new NumericBetween(['reps'], array('low' => 1, 'high' => 15)));

            $form->addField('inputtext', ['name' => 'rounding', 'label' => 'Rounding', 
                'title' => "Enter the rounding value (0.01 - 20). This will typically be twice the smallest weight plate you have.", 
                'style' => "width: 5em;"])
                ->addFilter(new FloatVal())
                ->addValidator(new Compulsory(['rounding']))
                ->addValidator(new NumericBetween(['weight'], array('low' => 0.01, 'high' => 20)));

        $form->addField('divclose', ['name' => 'row1close']);
        $form->addField('buttonsubmit', ['name' => 'submit', 'value' => 'Submit']);

        $form->setAutofocus('weight');

        return $form;
    }

    /**
     * Dispatch the action.
     */
    public function dispatch()
    {
        $payload = new Payload();

        $form = $this->createForm()->load($payload);
        $payload->set('form', $form);

        $payload->set('af', 'weight');
        $payload->set('results', []);
        $payload->set('percents', []);


        // Has user posted the form?
        if ($this->input->isPost()) {

            $payload->set('weight', $this->input->post('weight', ''));
            $payload->set('reps', $this->input->post('reps', ''));
            $payload->set('rounding', $this->input->post('rounding', 2.5));

            if ($form->validate($this->input->post()->toArray())) {
                $results = array();
                $calculator = new OnermCalcs();
                $average = $calculator->onermcalcs(floatval($payload->weight), intval($payload->reps), 
                    floatval($payload->rounding), $results);

                $payload->set('results', $results);
                $payload->set('average', $average);

                $percents = array();
                $calculator->onermpercents(floatval($average->value), floatval($payload->rounding), $percents);
                $payload->set('percents', $percents);
            }


            $form->save($payload);
        }

        if ($form->getPersistHandler()->hasDebugging()) {
            $form->getPersistHandler()->outputDebugging($this->container->get('logger'));
        }

        $responder = new OnermResponder($this->container, $this->output, $payload);
        $responder->dispatch();
    }

}
