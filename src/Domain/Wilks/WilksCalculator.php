<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Domain\Wilks;

use WTCalcs\Domain\Wilks\AbstractCalculator;
use WTCalcs\Domain\Wilks\WilksResult;

/**
 * The Wilks calculator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WilksCalculator extends AbstractCalculator
{
    /**
     * Calculation coefficients.
     * @var array
     */
    protected $calc = array(
        'male'      => array( 16.2606339,    -0.002388645,   -0.00113732,    0.00000701863, -0.00000001291, -216.0475144),
        'female'    => array(-27.23842536447, 0.82112226871, -0.00930733913, 0.00004731582, -0.00000009054,  594.31747775582)
    );

    /**
     * Calculate the wilks score.
     *
     * @param   float       $weight     Weight lifted.(KG)
     * @param   float       $bodyWeight Body weight.(KG)
     * @param   string      $gender     Gender.
     * @param   int|null    $age        Age.
     * @param   string      $tag        Name tag.
     *
     * @return  WilksResult         Wilks calculations.
     */
    public function wilks(float $weight, float $bodyWeight, string $gender, ?int $age = null, 
        string $tag = 'Wilks'): WilksResult
    {
        $wilks = 0.0;

        $c = $this->calc[$gender];

        $wilks =  $c[0] * $bodyWeight;
        $wilks += $c[1] * $bodyWeight ** 2;
        $wilks += $c[2] * $bodyWeight ** 3;
        $wilks += $c[3] * $bodyWeight ** 4;
        $wilks += $c[4] * $bodyWeight ** 5;
        $wilks += $c[5];
        $wilks = 500 / $wilks;

        $bwMult = $wilks;
        $wilks = $wilks * $weight;

        if (is_null($age)) {
            return new WilksResult($tag, $wilks, $bwMult);
        } else {
            $ret = $this->applyAge($wilks, $age);
            return new WilksResult($tag . '/Age', $ret[0], $ret[1]);
        }
    }    

}
