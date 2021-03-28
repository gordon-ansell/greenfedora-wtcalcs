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
use GreenFedora\Http\CookieHandler;

use WTCalcs\Adr\Domain\WilksCalculator;

/**
 * The Wilks score calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WilksAction extends AbstractAction implements ActionInterface
{
    const KGMULT = 0.45359237;

    /**
     * Dispatch the action.
     */
    public function dispatch()
    {
        $payload = new Payload();
        $cookieHandler = new CookieHandler($this->input, 
            array(
                'gender' => 'male', 
                'bodyWeight' => 75, 
                'bodyWeightUnits' => 'kg', 
                'weight' => 100, 
                'weightUnits' => 'kg', 
                'squat' => 100,
                'squatUnits' => 'kg',
                'bench' => 100,
                'benchUnits' => 'kg',
                'dead' => 100,
                'deadUnits' => 'kg',
                'age' => 0,
                'method' => 'all',
            ), 'wilks_');
        $cookieHandler->load($payload);

        // Has user posted the form?
        if ($this->input->isPost()) {
            $payload->set('gender', $this->input->post('gender', 'male'));
            $payload->set('bodyWeight', floatval($this->input->post('bodyWeight', 75)));
            $payload->set('bodyWeightUnits', $this->input->post('bodyWeightUnits', 'kg'));
            $payload->set('weight', floatval($this->input->post('weight', 100)));
            $payload->set('weightUnits', $this->input->post('weightUnits', 'kg'));
            $payload->set('age', intval($this->input->post('age', 0)));
            $payload->set('method', $this->input->post('method', 'all'));
            $payload->set('squat', floatval($this->input->post('squat', 100)));
            $payload->set('squatUnits', $this->input->post('squatUnits', 'kg'));
            $payload->set('bench', floatval($this->input->post('bench', 100)));
            $payload->set('benchUnits', $this->input->post('benchUnits', 'kg'));
            $payload->set('dead', floatval($this->input->post('dead', 100)));
            $payload->set('deadUnits', $this->input->post('deadUnits', 'kg'));

            $convWeight = 0;
            $convBodyWeight = 0;

            if ('all' == $payload->get('method')) {
                if ('lb' == $payload->get('weightUnits')) {
                    $convWeight = $payload->get('weight') * self::KGMULT;
                } else {
                    $convWeight = $payload->get('weight');
                }
            } else {
                foreach (['squat', 'bench', 'dead'] as $e) {
                    if ('lb' == $payload->get($e . 'Units')) {
                        $convWeight += $payload->get($e) * self::KGMULT;
                    } else {
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
            $results[] = $calculator->wilks($convWeight, $convBodyWeight, $payload->gender);
            if ($payload->age and $payload->age > 13) {
                $results[] = $calculator->wilksAge($convWeight, $convBodyWeight, $payload->gender, $payload->age);
            }

            $payload->set('results', $results);

            $cookieHandler->save($payload);
        }

        $responder = new WilksResponder($this->container, $this->output, $payload);
        $responder->dispatch();
    }

}
