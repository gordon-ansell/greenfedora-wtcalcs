<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Domain\Wilks;

use GreenFedora\Adr\Domain\ModelInterface;

use WTCalcs\Adr\Domain\Wilks\AbstractCalculator;
use WTCalcs\Adr\Domain\Wilks\WilksResult;

/**
 * The Wilks calculator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WilksCalculator extends AbstractCalculator implements ModelInterface
{
    /**
     * Calculate the wilks score.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   string  $gender     Gender.
     *
     * @return  WilksResult         Wilks calculations.
     */
    public function wilks(float $weight, float $bodyWeight, string $gender): WilksResult
    {
        $wilks = 0.0;

        if ("female" == $gender) {
            $wilks = -27.23842536447 * $bodyWeight;
            $wilks += 0.82112226871 * $bodyWeight ** 2;
            $wilks += -0.00930733913 * $bodyWeight ** 3;
            $wilks += 0.00004731582 * $bodyWeight ** 4;
            $wilks += -0.00000009054 * $bodyWeight ** 5;
            $wilks += 594.31747775582;
            $wilks = 500 / $wilks;
        } else {
            $wilks = 16.2606339 * $bodyWeight;
            $wilks += -0.002388645 * $bodyWeight ** 2;
            $wilks += -0.00113732 * $bodyWeight ** 3;
            $wilks += 0.00000701863 * $bodyWeight ** 4;
            $wilks += -0.00000001291 * $bodyWeight ** 5;
            $wilks -= 216.0475144;
            $wilks = 500 / $wilks;
        }

        $bwMult = $wilks;
        $wilks = $wilks * $weight;

        return new WilksResult('Wilks', $wilks, ['mult' => $bwMult]);
    }

    /**
     * Calculate the wilks score with age allowances
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   string  $gender     Gender.
     * @param   int     $age        Age.   
     *
     * @return  WilksResult         Wilks calculations.
     */
    public function wilksAge(float $weight, float $bodyWeight, string $gender, int $age): WilksResult
    {
        $wilksAge = $this->wilks($weight, $bodyWeight, $gender)->value;
        $ret = $this->applyAge($wilksAge, $age);

        return new WilksResult('Wilks/Age', ($ret[0]), ['mult' => $ret[1]]);
    }
}
