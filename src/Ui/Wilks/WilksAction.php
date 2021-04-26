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
use GreenFedora\Application\ResponseInterface;
use WTCalcs\Ui\Wilks\WilksResponder;
use GreenFedora\Validator\Compulsory;
use GreenFedora\Validator\Integer;
use GreenFedora\Validator\NumericBetween;
use GreenFedora\Form\FormPersistHandler;
use GreenFedora\Form\Form;
use GreenFedora\Form\FormInterface;

use WTCalcs\Domain\Wilks\WilksDomain;

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
     * 
     * @return HttpResponseInterface
     */
    public function dispatch(): ResponseInterface
    {
        $data = $this->payload->getData();
        $form = $this->createForm()->load($data);
        $this->payload->setData($data);
        $this->payload->set('form', $form);

        $this->payload->set('resultsWilks', []);
        $this->payload->set('resultsAllometric', []);
        $this->payload->set('resultsSiff', []);

        $this->payload->set('formSubmitted', null);

        // Has user posted the form?
        if ($this->request->isPost() and $this->request->formSubmitted('wilks')) {

            $this->payload->setFormSubmitted('wilks');

            $this->payload->setFrom($this->request->post()->toArray(), $this->getFormDefaults());

            if ($form->validate($this->request->post()->toArray())) {

                $params = [
                    'gender'    => $this->payload->get('gender'),
                    'age'       => $this->payload->get('age'),
                    'method'    => $this->payload->get('method'),
                ];

                foreach (['weight', 'squat', 'bench', 'dead', 'bodyWeight'] as $item) {
                    $params[$item] = array($this->payload->get($item), $this->payload->get($item . 'Units'));
                }

                $wd = new WilksDomain($params);
                list($resultsWilks, $resultsAllometric, $resultsSiff) = $wd->results();

                $this->payload->set('resultsWilks', $resultsWilks);
                $this->payload->set('resultsAllometric', $resultsAllometric);
                $this->payload->set('resultsSiff', $resultsSiff);
            }

            $form->save($this->payload->getData());
        }

        $responder = new WilksResponder($this->container, $this->request, $this->response, $this->payload);
        return $responder->respond();
    }

}
