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
use WTCalcs\Adr\Responder\WilksResponder;
use GreenFedora\Payload\Payload;
use GreenFedora\Payload\PayloadInterface;
use GreenFedora\Validator\Compulsory;
use GreenFedora\Validator\Numeric;
use GreenFedora\Validator\Integer;
use GreenFedora\Validator\NumericBetween;
use GreenFedora\Filter\FloatVal;
use GreenFedora\Filter\IntVal;
use GreenFedora\Form\FormValidator;
use GreenFedora\Form\FormPersistHandler;
use GreenFedora\Form\FormPersistHandlerInterface;
use GreenFedora\Form\Form;
use GreenFedora\Form\FormInterface;

use WTCalcs\Adr\Domain\Wilks\WilksCalculator;
use WTCalcs\Adr\Domain\Wilks\AllometricCalculator;
use WTCalcs\Adr\Domain\Wilks\SiffCalculator;

/**
 * The Wilks score calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WilksAction extends AbstractAction implements ActionInterface
{
    const KGMULT = 0.45359237;

    /**
     * Add a weight/units pair of fields.
     * 
     * @param   FormInterface   $form   Form to add it to.
     * @param   string          $name   Name of field.
     * @param   string          $label  Label.
     * @param   string          $title  Title field.
     * @return  FormInterface
     */
    protected function weightUnits(FormInterface &$form, string $name, string $label, string $title): FormInterface
    {
        $form->addField('inputtext', ['name' => $name, 'label' => $label, 
            'title' => $title])
            ->addValidator(new Compulsory([$name]))
            ->addValidator(new NumericBetween([$name], array('low' => 1, 'high' => 9999.99)));

        $form->addField('select', ['name' => $name . 'Units', 'label' => 'Units',
            'options' => ['kg' => 'kg', 'lb' => 'lb'],
            'title' => "Select the units."])
            ->setAfter('<span class="caret">&#9660;</span>');

        return $form;
    }

    /**
     * Create the form.
     * 
     * @return  FormInterface
     */
    protected function createForm()
    {
        $method = $this->input->post('method', $this->getInstance('session')->get('wilks_method', 'all'));

        $ph = new FormPersistHandler($this->getInstance('session'), $this->input, 
            array(
                'gender' => 'male', 
                'age' => '',
                'bodyWeight' => '', 
                'bodyWeightUnits' => 'kg', 
                'method' => 'all',
                'weight' => '', 
                'weightUnits' => 'kg', 
                'squat' => '',
                'squatUnits' => 'kg',
                'bench' => '',
                'benchUnits' => 'kg',
                'dead' => '',
                'deadUnits' => 'kg',
            ), 'wilks_');

        $form = new Form('/wilks#results', $ph, ['onload' => "javascript:methodCheck();"]);
        $form->setAutoWrap('fieldset');

        $form->addField('errors', ['name' => 'errors', 'class' => 'error']);

        // Row 1. Gender, age.

        $form->addField('divopen', ['name' => 'row1', 'class' => 'two-columns-always']);

            $form->addField('radioset', ['name' => 'gender', 'label' => 'Gender', 'class' => 'radio horizontal', 
                'options' => ['male' => 'Male', 'female' => 'Female'], 'title' => "Select your gender."]);

            $form->addField('inputtext', ['name' => 'age', 'label' => 'Age', 
                'title' => "If you want to see the adjustment for your age, enter your age here."])
                ->addValidator(new Integer(['age']))
                ->addValidator(new NumericBetween(['age'], array('low' => 14, 'high' => 90)));

        $form->closeField();

        // Row 2. Bodyweight.

        $form->addField('divopen', ['name' => 'row2', 'class' => 'two-columns-always']);

            $this->weightUnits($form, 'bodyWeight', 'Body Weight', "Enter your body weight.");

        $form->closeField();

        // Row 3. Method.

        $form->addField('radioset', ['name' => 'method', 'label' => 'Method', 'class' => 'radio horizontal', 
            'options' => ['all' => 'Total', 'separate' => 'Separate Lifts'], 'onclick' => "javascript:methodCheck();",
            'title' => "Select the method of calculation: total of all lifts together or enter weights for each lift separately?"]);

        // Work out the styles for all/separate.

        $styleAll = 'display:grid';
        $styleSeparate = 'display:none';
        if ("separate" == $method) {
            $styleAll = "display:none";
            $styleSeparate = "display:inline";
        }

        // Row 4. Weight.

        $form->addField('divopen', ['name' => 'row4', 'id' => 'methodAll', 'class' => 'two-columns-always', 'style' => $styleAll]);

            $this->weightUnits($form, 'weight', 'Total Weight Lifted',
                "This can be for an invididual lift, but a true Wilks score is based on your combined squat, bench press and deadlift weights."
            );
            if ("separate" == $method) {
                $form->getField('weight')->disableValidators();
            }

        $form->closeField();

        // Rows 5-7. Squat, Bench Press, Deadlift.

        $rows = ['squat' => 'Squat', 'bench' => 'Bench Press', 'dead' => 'Deadlift'];
        $count = 5;

        $form->addField('spanopen', ['name' => 'allblock', 'id' => 'methodSeparate', 'style' => $styleSeparate]);

            foreach ($rows as $k => $v) {
                $form->addField('divopen', ['name' => 'row' . $count, 'class' => 'two-columns-always']);

                    $this->weightUnits($form, $k, $v, "Enter the weight you lifted in the " . $k . ".");

                    if ("all" == $method) {
                        $form->getField($k)->disableValidators();
                    }
            
                $form->closeField();
                $count++;
            }

        $form->closeField();

        // Last row.

        $form->addField('buttonsubmit', ['name' => 'submit', 'value' => 'Submit']);

        $form->setAutofocus('age');

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

        $payload->set('results', []);

        // Has user posted the form?
        if ($this->input->isPost()) {

            $payload->set('gender', $this->input->post('gender', 'male'));
            $payload->set('age', $this->input->post('age', ''));
            $payload->set('bodyWeight', $this->input->post('bodyWeight', ''));
            $payload->set('bodyWeightUnits', $this->input->post('bodyWeightUnits', 'kg'));
            $payload->set('method', $this->input->post('method', 'all'));
            $payload->set('weight', $this->input->post('weight', ''));
            $payload->set('weightUnits', $this->input->post('weightUnits', 'kg'));
            $payload->set('squat', $this->input->post('squat', ''));
            $payload->set('squatUnits', $this->input->post('squatUnits', 'kg'));
            $payload->set('bench', $this->input->post('bench', ''));
            $payload->set('benchUnits', $this->input->post('benchUnits', 'kg'));
            $payload->set('dead', $this->input->post('dead', ''));
            $payload->set('deadUnits', $this->input->post('deadUnits', 'kg'));

            if ($form->validate($this->input->post()->toArray())) {

                $convWeight = 0;
                $convBodyWeight = 0;
                $convSingles = array();

                if ('all' == $payload->get('method')) {
                    if ('lb' == $payload->get('weightUnits')) {
                        $convWeight = $payload->get('weight') * self::KGMULT;
                    } else {
                        $convWeight = $payload->get('weight');
                    }
                } else {
                    foreach (['squat', 'bench', 'dead'] as $e) {
                        if ('lb' == $payload->get($e . 'Units')) {
                            $convSingles[$e] = $payload->get($e) * self::KGMULT;
                            $convWeight += $payload->get($e) * self::KGMULT;
                        } else {
                            $convSingles[$e] = $payload->get($e);
                            $convWeight += $payload->get($e);
                        }
                    }

                    $payload->set('weight', $convWeight);
                    $payload->set('weightUnits', 'kg');
                }

                if ('lb' == $payload->get('bodyWeightUnits')) {
                    $convBodyWeight = $payload->get('bodyWeight') * self::KGMULT;
                } else {
                    $convBodyWeight = $payload->get('bodyWeight');
                }

                $results = [];

                $calculator = new WilksCalculator();
                $results[] = $calculator->wilks(floatval($convWeight), floatval($convBodyWeight), $payload->gender);
                if ($payload->age and $payload->age > 13) {
                    $results[] = $calculator->wilksAge(floatval($convWeight), floatval($convBodyWeight), 
                        $payload->gender, intval($payload->age));
                }

                if ('separate' == $payload->get('method')) {
                    $allo = new AllometricCalculator();
                    $results[] = $allo->squat(floatval($convSingles['squat']), floatval($convBodyWeight));
                    if ($payload->age and $payload->age > 13) {
                        $results[] = $allo->squatAge(floatval($convSingles['squat']), floatval($convBodyWeight), 
                        intval($payload->age));
                    }
                    $results[] = $allo->bench(floatval($convSingles['bench']), floatval($convBodyWeight));
                    if ($payload->age and $payload->age > 13) {
                        $results[] = $allo->benchAge(floatval($convSingles['bench']), floatval($convBodyWeight),
                        intval($payload->age));
                    }
                    $results[] = $allo->dead(floatval($convSingles['dead']), floatval($convBodyWeight));
                    if ($payload->age and $payload->age > 13) {
                        $results[] = $allo->deadAge(floatval($convSingles['dead']), floatval($convBodyWeight),
                        intval($payload->age));
                    }
                }

                $siff = new SiffCalculator();
                if ('separate' == $payload->get('method')) {
                    $results[] = $siff->squat(floatval($convSingles['squat']), floatval($convBodyWeight));
                    if ($payload->age and $payload->age > 13) {
                        $results[] = $siff->squatAge(floatval($convSingles['squat']), floatval($convBodyWeight), 
                        intval($payload->age));
                    }
                    $results[] = $siff->bench(floatval($convSingles['bench']), floatval($convBodyWeight));
                    if ($payload->age and $payload->age > 13) {
                        $results[] = $siff->benchAge(floatval($convSingles['bench']), floatval($convBodyWeight),
                        intval($payload->age));
                    }
                    $results[] = $siff->dead(floatval($convSingles['dead']), floatval($convBodyWeight));
                    if ($payload->age and $payload->age > 13) {
                        $results[] = $siff->deadAge(floatval($convSingles['dead']), floatval($convBodyWeight),
                        intval($payload->age));
                    }
                    $results[] = $siff->total(floatval($convSingles['dead'] + $convSingles['bench'] + $convSingles['squat']), 
                        floatval($convBodyWeight));
                    if ($payload->age and $payload->age > 13) {
                        $results[] = $siff->totalAge(floatval($convSingles['dead'] + $convSingles['bench'] + $convSingles['squat']), 
                            floatval($convBodyWeight), intval($payload->age));
                    }
                } else {
                    $results[] = $siff->total(floatval($convWeight), floatval($convBodyWeight));
                    if ($payload->age and $payload->age > 13) {
                        $results[] = $siff->totalAge(floatval($convWeight), floatval($convBodyWeight), intval($payload->age));
                    }
                }

                $payload->set('results', $results);
            }

            $form->save($payload);
        }

        if ($form->getPersistHandler()->hasDebugging()) {
            $form->getPersistHandler()->outputDebugging($this->container->get('logger'));
        }

        $responder = new WilksResponder($this->container, $this->output, $payload);
        $responder->dispatch();
    }

}
