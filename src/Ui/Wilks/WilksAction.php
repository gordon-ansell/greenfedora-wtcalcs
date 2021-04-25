<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Ui\Wilks;

use GreenFedora\Http\Adr\AbstractHttpAction;
use GreenFedora\Application\Adr\ActionInterface;
use WTCalcs\Ui\Wilks\WilksResponder;
use GreenFedora\Payload\Payload;
use GreenFedora\Validator\Compulsory;
use GreenFedora\Validator\Integer;
use GreenFedora\Validator\NumericBetween;
use GreenFedora\Form\FormPersistHandler;
use GreenFedora\Form\Form;
use GreenFedora\Form\FormInterface;

use WTCalcs\Domain\Wilks\WilksCalculator;
use WTCalcs\Domain\Wilks\AllometricCalculator;
use WTCalcs\Domain\Wilks\SiffCalculator;

/**
 * The Wilks score calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WilksAction extends AbstractHttpAction implements ActionInterface
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
        $form->addField('weight', ['name' => $name, 'label' => $label, 'type' => 'number', 'step' => 'any', 'min' => '1',
            'title' => $title])
            ->addValidator(new Compulsory([$name]))
            ->addValidator(new NumericBetween([$name], array('low' => 1, 'high' => 9999.99)));


        return $form;
    }

    /**
     * Get the form defaults.
     * 
     * @return array
     */
    protected function getFormDefaults(): array
    {
        return array(
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
        );        
    }

    /**
     * Create the form.
     * 
     * @return  FormInterface
     */
    protected function createForm()
    {
        $method = $this->request->post('method', $this->get('session')->get('wilks_method', 'all'));

        $ph = new FormPersistHandler($this->get('session'), $this->request, $this->getFormDefaults(), 'wilks_');

        $form = new Form('wilks', '/wilks#results', $ph, ['onload' => "javascript:methodCheck();"]);
        $form->setAutoWrap('fieldset');

        $form->addField('errors', ['name' => 'errors', 'class' => 'error']);

        // Row 1. Gender, age.

        $form->addField('divopen', ['name' => 'row1', 'class' => 'three-columns']);

            $form->addField('radioset', ['name' => 'gender', 'label' => 'Gender', 'class' => 'radio horizontal', 
                'options' => ['male' => 'Male', 'female' => 'Female'], 'title' => "Select your gender."]);

                $this->weightUnits($form, 'bodyWeight', 'Body Weight', "Enter your body weight.");

                $form->addField('inputnumber', ['name' => 'age', 'label' => 'Age', 'style' => 'max-width: 4em',
                    'title' => "If you want to see the adjustment for your age, enter your age here. Whole numbers only.",
                    'step' => '1', 'min' => '14', 'max' => '90'])
                    ->addValidator(new Integer(['age']))
                    ->addValidator(new NumericBetween(['age'], array('low' => 14, 'high' => 90)));

            $form->closeField();

        // Row 2. Method, bodyweight.

        $form->addField('radioset', ['name' => 'method', 'label' => 'Method', 'class' => 'radio horizontal', 
            'options' => ['all' => 'Total', 'separate' => 'Separate'], 'onclick' => "javascript:methodCheck();",
            'title' => "Select the method of calculation: total of all lifts together or enter weights for each lift separately?"]);


        // Work out the styles for all/separate.

        $styleAll = 'display:grid';
        $styleSeparate = 'display:none';
        if ("separate" == $method) {
            $styleAll = "display:none";
            $styleSeparate = "display:inline";
        }

        // Row 3. Weight.

        $form->addField('divopen', ['name' => 'row3', 'id' => 'methodAll', 'style' => $styleAll]);

            $this->weightUnits($form, 'weight', 'Total Weight Lifted',
                "This can be for an invididual lift, but a true Wilks score is based on your combined squat, bench press and deadlift weights."
            );
            if ("separate" == $method) {
                $form->getField('weight')->disableValidators();
            }

        $form->closeField();

        // Rows 4-6. Squat, Bench Press, Deadlift.

        $rows = ['squat' => 'Squat', 'bench' => 'Bench Press', 'dead' => 'Deadlift'];
        $count = 5;


        $form->addField('spanopen', ['name' => 'allblock', 'id' => 'methodSeparate', 'style' => $styleSeparate]);

                foreach ($rows as $k => $v) {
                    $form->addField('divopen', ['name' => 'row' . $count]);

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
        $form = $this->createForm()->load($this->payload);
        $this->payload->set('form', $form);

        $this->payload->set('resultsWilks', []);
        $this->payload->set('resultsAllometric', []);
        $this->payload->set('resultsSiff', []);

        // Has user posted the form?
        if ($this->request->isPost() and $this->request->formSubmitted('wilks')) {

            $this->payload->setFrom($this->request->post()->toArray(), $this->getFormDefaults());

            if ($form->validate($this->request->post()->toArray())) {

                $convWeight = 0;
                $convBodyWeight = 0;
                $convSingles = array();

                if ('all' == $this->payload->get('method')) {
                    if ('lb' == $this->payload->get('weightUnits')) {
                        $convWeight = $this->payload->get('weight') * self::KGMULT;
                    } else {
                        $convWeight = $this->payload->get('weight');
                    }
                } else {
                    foreach (['squat', 'bench', 'dead'] as $e) {
                        if ('lb' == $this->payload->get($e . 'Units')) {
                            $convSingles[$e] = $this->payload->get($e) * self::KGMULT;
                            $convWeight += $this->payload->get($e) * self::KGMULT;
                        } else {
                            $convSingles[$e] = $this->payload->get($e);
                            $convWeight += $this->payload->get($e);
                        }
                    }

                    $this->payload->set('weight', $convWeight);
                    $this->payload->set('weightUnits', 'kg');
                }

                if ('lb' == $this->payload->get('bodyWeightUnits')) {
                    $convBodyWeight = $this->payload->get('bodyWeight') * self::KGMULT;
                } else {
                    $convBodyWeight = $this->payload->get('bodyWeight');
                }

                $resultsWilks = [];
                $resultsAllometric = [];
                $resultsSiff = [];

                $calculator = new WilksCalculator();
                if ('separate' == $this->payload->get('method')) {
                    foreach (['squat', 'bench', 'dead'] as $lift) {
                        $resultsWilks[] = $calculator->wilks(floatval($convSingles[$lift]), floatval($convBodyWeight), 
                            $this->payload->gender, null, ucfirst($lift));
                        if ($this->payload->age and $this->payload->age > 13) {
                            $resultsWilks[] = $calculator->wilks(floatval($convSingles[$lift]), floatval($convBodyWeight), 
                                $this->payload->gender, intval($this->payload->age), ucfirst($lift));
                        }
                    }
                }

                $resultsWilks[] = $calculator->wilks(floatval($convWeight), floatval($convBodyWeight), $this->payload->gender);
                if ($this->payload->age and $this->payload->age > 13) {
                    $resultsWilks[] = $calculator->wilks(floatval($convWeight), floatval($convBodyWeight), 
                        $this->payload->gender, intval($this->payload->age));
                }

                $allo = new AllometricCalculator();
                if ('separate' == $this->payload->get('method')) {
                    foreach (['squat', 'bench', 'dead'] as $lift) {
                        $resultsAllometric[] = $allo->squat(floatval($convSingles[$lift]), floatval($convBodyWeight));
                        if ($this->payload->age and $this->payload->age > 13) {
                            $resultsAllometric[] = $allo->squat(floatval($convSingles[$lift]), floatval($convBodyWeight), 
                            intval($this->payload->age));
                        }
                    }
                    $resultsAllometric[] = $allo->total(floatval($convSingles['dead'] + $convSingles['bench'] + $convSingles['squat']), 
                        floatval($convBodyWeight));
                    if ($this->payload->age and $this->payload->age > 13) {
                        $resultsAllometric[] = $allo->total(floatval($convSingles['dead'] + $convSingles['bench'] + $convSingles['squat']), 
                            floatval($convBodyWeight), intval($this->payload->age));
                    }
                } else {
                    $resultsAllometric[] = $allo->total(floatval($convWeight), floatval($convBodyWeight));
                    if ($this->payload->age and $this->payload->age > 13) {
                        $resultsAllometric[] = $allo->total(floatval($convWeight), 
                            floatval($convBodyWeight), intval($this->payload->age));
                    }
                }

                $siff = new SiffCalculator();
                if ('separate' == $this->payload->get('method')) {
                    foreach (['squat', 'bench', 'dead'] as $lift) {
                        $resultsSiff[] = $siff->squat(floatval($convSingles[$lift]), floatval($convBodyWeight));
                        if ($this->payload->age and $this->payload->age > 13) {
                            $resultsSiff[] = $siff->squat(floatval($convSingles[$lift]), floatval($convBodyWeight), 
                            intval($this->payload->age));
                        }
                    }
                    $resultsSiff[] = $siff->total(floatval($convSingles['dead'] + $convSingles['bench'] + $convSingles['squat']), 
                        floatval($convBodyWeight));
                    if ($this->payload->age and $this->payload->age > 13) {
                        $resultsSiff[] = $siff->total(floatval($convSingles['dead'] + $convSingles['bench'] + $convSingles['squat']), 
                            floatval($convBodyWeight), intval($this->payload->age));
                    }
                } else {
                    $resultsSiff[] = $siff->total(floatval($convWeight), floatval($convBodyWeight));
                    if ($this->payload->age and $this->payload->age > 13) {
                        $resultsSiff[] = $siff->total(floatval($convWeight), floatval($convBodyWeight), intval($this->payload->age));
                    }
                }

                $this->payload->set('resultsWilks', $resultsWilks);
                $this->payload->set('resultsAllometric', $resultsAllometric);
                $this->payload->set('resultsSiff', $resultsSiff);
            }

            $form->save($this->payload);
        }

        if ($form->getPersistHandler()->hasDebugging()) {
            $form->getPersistHandler()->outputDebugging($this->container->get('logger'));
        }

        $responder = new WilksResponder($this->container, $this->request, $this->response, $this->payload);
        $responder->dispatch();
    }

}
