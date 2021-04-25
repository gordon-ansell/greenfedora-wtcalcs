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
 * The allometric calculator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class AllometricCalculator extends AbstractCalculator
{
    /**
     * Calculation values.
     * @var array
     */
    protected $calc = array(
        'squat'     => -0.60,
        'bench'     => -0.57,
        'dead'      => -0.46,
        'total'     => -0.54
    );

    /**
     * Do the calculation.
     * 
     * @param   string  $type       Type.
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function doCalc(string $type, float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        $mult = $bodyWeight ** $this->calc[$type];
        $result = $weight * $mult;

        if (is_null($age)) {
            return new WilksResult(ucfirst($type), $result, $mult);
        } else {
            $r = $this->applyAge($result, $age);
            return new WilksResult(ucfirst($type) . '/Age', $r[0], $r[1]);    
        }
    }

    /**
     * Calculate the allometric squat result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function squat(float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        return $this->doCalc('squat', $weight, $bodyWeight, $age);
    }

    /**
     * Calculate the allometric bench result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function bench(float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        return $this->doCalc('bench', $weight, $bodyWeight, $age);
    }

    /**
     * Calculate the allometric deadlift result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function dead(float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        return $this->doCalc('dead', $weight, $bodyWeight, $age);
    }

    /**
     * Calculate the allometric total result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function total(float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        return $this->doCalc('total', $weight, $bodyWeight, $age);
    }

}
