<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Ui\Onerm;

use GreenFedora\Http\Adr\AbstractHttpAction;
use GreenFedora\Application\Adr\ActionInterface;
use WTCalcs\Ui\Onerm\OnermResponder;
use GreenFedora\Payload\PayloadInterface;
use GreenFedora\Validator\Compulsory;
use GreenFedora\Validator\Integer;
use GreenFedora\Validator\NumericBetween;
use GreenFedora\Filter\FloatVal;
use GreenFedora\Form\FormPersistHandler;
use GreenFedora\Form\Form;

use WTCalcs\Domain\Onerm\OnermDomain;

/**
 * The 1-rep maximum calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class OnermAction extends AbstractHttpAction implements ActionInterface
{

    /**
     * Get the form defaults.
     * 
     * @return array
     */
    protected function getFormDefaults(): array
    {
        return array(
            'weight' => '', 
            'reps' => 2, 
            'rounding' => 2.5
        );        
    }

    /**
     * Create the form.
     * 
     * @return  FormInterface
     */
    protected function createForm()
    {
        $ph = new FormPersistHandler($this->get('session'), $this->request, $this->getFormDefaults(), 'onerm_');

        $form = new Form('onerm', '/onerm', $ph);
        $form->setAutoWrap('fieldset');

        $form->addField('errors', ['name' => 'errors', 'class' => 'error']);
        $form->addField('divopen', ['name' => 'row1', 'class' => 'three-columns-always']);

            $form->addField('inputnumber', ['name' => 'weight', 'label' => 'Weight', 
                'placeholder' => 'Weight', 'title' => "Enter the weight you lifted (5-9999.99).",
                'step' => 'any', 'min' => '1'])
                ->addFilter(new FloatVal())
                ->addValidator(new Compulsory(['weight']))
                ->addValidator(new NumericBetween(['weight'], array('low' => 5, 'high' => 9999.99)));


            $form->addField('inputnumber', ['name' => 'reps', 'label' => 'Reps', 
                'title' => "Enter the number of reps you performed (1-15).", 'style' => "width: 4em;",
                'step' => '1', 'min' => '1', 'max' => '15', 'step' => '1'])
                ->addValidator(new Compulsory(['reps']))
                ->addValidator(new Integer(['reps']))
                ->addValidator(new NumericBetween(['reps'], array('low' => 1, 'high' => 15)));

            $form->addField('inputnumber', ['name' => 'rounding', 'label' => 'Rounding', 
                'title' => "Enter the rounding value (0.01 - 20). This will typically be twice the smallest weight plate you have.", 
                'style' => "width: 5em;",
                'step' => 'any', 'min' => '0.01', 'max' => '20'])
                ->addFilter(new FloatVal())
                ->addValidator(new Compulsory(['rounding']))
                ->addValidator(new NumericBetween(['weight'], array('low' => 0.01, 'high' => 20)));


        $form->addField('divclose', ['name' => 'row1close']);
        $form->addField('buttonsubmit', ['name' => 'submit', 'value' => 'Submit']);

        $form->setAutofocus('weight');

        return $form;
    }

    /**
     * Get the results.
     * 
     * @param   PayloadInterface    $payload    Payload.
     * @return  void
     */
    public function results(PayloadInterface &$payload)
    {
        $calculator = new OnermDomain();
        list($onerms, $average, $percents) = $calculator->results($payload->weight, $payload->reps, $payload->rounding);

        $payload->set('results', $onerms);
        $payload->set('average', $average);
        $payload->set('percents', $percents);
    }

    /**
     * Dispatch the action.
     */
    public function dispatch()
    {
        $form = $this->createForm()->load($this->payload);
        $this->payload->set('form', $form);

        //$this->payload->set('af', 'weight');
        $this->payload->set('results', []);
        $this->payload->set('percents', []);

        // Has user posted the form?
        if ($this->request->formSubmitted('onerm')) {

            $this->payload->setFrom($this->request->post()->toArray(), $this->getFormDefaults());

            if ($form->validate($this->request->post()->toArray())) {
                $this->results($this->payload);
            }

            $form->save($this->payload);

        } else if ($this->request->formSubmitted('onerm-table')) {

            $session = $this->get('session');

            $this->payload->setFrom($session->getAllUnprefixed('onerm_'));
            $this->results($this->payload);
        }

        if ($form->getPersistHandler()->hasDebugging()) {
            $form->getPersistHandler()->outputDebugging($this->container->get('logger'));
        }

        $responder = new OnermResponder($this->container, $this->request, $this->response, $this->payload);
        $responder->dispatch();
    }

}
