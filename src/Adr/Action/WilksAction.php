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
use GreenFedora\Validator\Compulsory;
use GreenFedora\Validator\Numeric;
use GreenFedora\Validator\Integer;
use GreenFedora\Validator\NumericBetween;
use GreenFedora\Filter\FloatVal;
use GreenFedora\Filter\IntVal;
use GreenFedora\Form\FormValidator;
use GreenFedora\Form\FormPersistHandler;

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
     * Validation.
     * 
     * @return  null|array         Null if it worked, else error message and failed field.
     */
    protected function validate(): ?array
    {
        $method = $this->input->post('method', 'all');
        $fields = ['age', 'bodyWeight'];

        $fv = new FormValidator();

        $fv->addValidator('age', new Integer(['age']))
            ->addValidator('age', new NumericBetween(['age'], array('low' => 14, 'high' => 90)));

        $fv->addFilter('bodyWeight', new FloatVal())
            ->addValidator('bodyWeight', new Compulsory(['bodyWeight']))
            ->addValidator('bodyWeight', new NumericBetween(['bodyWeight'], array('low' => 20, 'high' => 1000)));

        if ('all' == $method) {
            $fv->addFilter('weight', new FloatVal())
                ->addValidator('weight', new Compulsory(['weight']))
                ->addValidator('weight', new NumericBetween(['weight'], array('low' => 1, 'high' => 9999.99)));
            $fields[] = 'weight';
        } else {
            $fv->addFilter('squat', new FloatVal())
                ->addValidator('squat', new Compulsory(['squat']))
                ->addValidator('squat', new NumericBetween(['squat'], array('low' => 1, 'high' => 9999.99)));
            $fields[] = 'squat';

            $fv->addFilter('bench', new FloatVal())
                ->addValidator('bench', new Compulsory(['bench']))
                ->addValidator('bench', new NumericBetween(['bench'], array('low' => 1, 'high' => 9999.99)));
            $fields[] = 'bench';

            $fv->addFilter('dead', new FloatVal())
                ->addValidator('dead', new Compulsory(['dead']))
                ->addValidator('dead', new NumericBetween(['dead'], array('low' => 1, 'high' => 9999.99)));
            $fields[] = 'dead';
        }
        
        $result = $fv->validate($this->input->post()->toArray(), $fields);

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
        $cookieHandler = new FormPersistHandler($this->getInstance('session'), $this->input, 
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
        $cookieHandler->load($payload);

        $payload->set('af', 'gender');
        $payload->set('error', '');
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

            $error = $this->validate();

            if (null !== $error) {
                $payload->set('error', $error[0]);
                $payload->set('af', $error[1]);

            } else {

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

            $cookieHandler->save($payload);
        }

        $responder = new WilksResponder($this->container, $this->output, $payload);
        $responder->dispatch();
    }

}
